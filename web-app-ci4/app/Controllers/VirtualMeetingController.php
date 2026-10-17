<?php
namespace App\Controllers;

use App\Libraries\GoogleMeetSessions;
use Config\Database;
use DomainException;

class VirtualMeetingController extends BaseController
{
    protected function meetings(): GoogleMeetSessions
    {
        return new GoogleMeetSessions(Database::connect());
    }

    private function authorized(array $roles): bool
    {
        return session()->get('isLoggedIn') && session()->get('user_id')
            && session()->get('role') !== null && in_array((int) session()->get('role'), $roles, true);
    }

    private function result(callable $operation)
    {
        $this->response->setHeader('Cache-Control', 'no-store');
        try {
            return $this->response->setJSON(['status' => 'success'] + ($operation() ?? []));
        } catch (DomainException $e) {
            return $this->response->setStatusCode(409)->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        } catch (\Throwable $e) {
            log_message('error', 'Meeting operation failed: ' . $e->getMessage());
            return $this->response->setStatusCode(503)->setJSON(['status' => 'error', 'message' => 'Meeting service unavailable. Please try again.']);
        }
    }

    private function denied()
    {
        return $this->response->setHeader('Cache-Control', 'no-store')->setStatusCode(403)
            ->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
    }

    public function requestMeeting()
    {
        if (!$this->authorized([0])) return $this->denied();
        return $this->result(function () {
            $this->meetings()->request((int) session()->get('user_id'));
            $this->logAudit('REQUEST_SESSION', 'student', (int) session()->get('user_id'));
            return ['message' => 'Session requested.'];
        });
    }

    public function cancelRequest()
    {
        if (!$this->authorized([0])) return $this->denied();
        return $this->result(function () {
            $this->meetings()->cancelRequest((int) session()->get('user_id'));
            $this->logAudit('CANCEL_SESSION_REQUEST', 'student', (int) session()->get('user_id'));
        });
    }

    public function getPendingRequest($studentId = null)
    {
        if (!$this->authorized([1])) return $this->denied();
        return $this->result(function () use ($studentId) {
            $state = $this->meetings()->status((int) $studentId);
            $active = $state['active'];
            $owned = $active && (int) $active['counselor_id'] === (int) session()->get('user_id');
            return ['pending' => !empty($state['pending']), 'has_meeting' => (bool) $active,
                'owned' => (bool) $owned, 'join_url' => $owned ? base_url('meeting/join/' . $active['id']) : null];
        });
    }

    public function start($studentId = null)
    {
        if (!$this->authorized([1])) return $this->denied();
        return $this->result(function () use ($studentId) {
            // The counselor creates the Google Meet in Google Workspace and confirms its admission settings.
            // StudentShield stores one newly created URL per counseling session; it does not create Meet spaces via OAuth.
            $meeting = $this->meetings()->publish((int) $studentId, (int) session()->get('user_id'),
                (string) $this->request->getPost('meet_link'), $this->request->getPost('host_confirmed') === '1');
            $this->logAudit('PUBLISH_SESSION', 'meeting', (int) $meeting['id']);
            return ['meeting_id' => $meeting['id'], 'join_url' => base_url('meeting/join/' . $meeting['id']),
                'message' => 'Google Meet published for this student.'];
        });
    }

    public function end($studentId = null)
    {
        if (!$this->authorized([1])) return $this->denied();
        return $this->result(function () use ($studentId) {
            $this->meetings()->end((int) $studentId, (int) session()->get('user_id'));
            $this->logAudit('CLOSE_SESSION_ACCESS', 'student', (int) $studentId);
            return ['message' => 'StudentShield access closed. End the call for everyone in Google Meet too.'];
        });
    }

    public function getActiveMeeting()
    {
        if (!$this->authorized([0])) return $this->denied();
        return $this->result(function () {
            $state = $this->meetings()->status((int) session()->get('user_id'));
            return ['has_meeting' => (bool) $state['active'], 'has_request' => (bool) $state['pending'],
                'join_url' => $state['active'] ? base_url('meeting/join/' . $state['active']['id']) : null];
        });
    }

    public function join($meetingId = null)
    {
        if (!$this->authorized([0, 1])) return $this->denied();
        try {
            $link = $this->meetings()->join((int) $meetingId, (int) session()->get('user_id'), (int) session()->get('role'));
            return redirect()->to($link)->setHeader('Cache-Control', 'no-store')->setHeader('Referrer-Policy', 'no-referrer');
        } catch (DomainException $e) {
            return $this->response->setStatusCode(403)->setHeader('Cache-Control', 'no-store')
                ->setContentType('text/plain')->setBody($e->getMessage());
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(503)->setContentType('text/plain')->setBody('Meeting service unavailable. Please try again.');
        }
    }
}
