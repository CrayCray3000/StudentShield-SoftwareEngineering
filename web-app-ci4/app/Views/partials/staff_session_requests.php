                <!-- Pending Meeting Requests (Live Polling Bell) -->
                <div class="relative" id="meetingNotifWrapper">
                    <button id="meetingBellBtn" onclick="toggleMeetingDropdown()"
                        class="staff-header-action relative flex items-center justify-center p-2 sm:px-3 sm:py-1.5 bg-white/10 hover:bg-white/20 rounded-full border border-white/15 transition-all duration-200 focus:outline-none"
                        aria-label="Session requests" aria-controls="meetingDropdown" aria-expanded="false">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs font-semibold text-primary hidden sm:inline ml-1.5">Requests</span>
                        <span id="meetingBadge"
                            class="<?= ($pending_meetings_count ?? 0) > 0 ? '' : 'hidden' ?> absolute -top-1 -right-1 flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-[#22c55e] text-white text-[10px] font-bold shadow-lg animate-pulse">
                            <?= esc($pending_meetings_count ?? 0) ?>
                        </span>
                    </button>

                    <div id="meetingDropdown" class="meeting-dropdown hidden" aria-labelledby="meetingDropdownTitle">
                        <div class="meeting-dropdown-heading">
                            <div>
                                <h2 id="meetingDropdownTitle">Session requests</h2>
                                <span id="dropdownCount" class="text-xs text-gray-500"></span>
                            </div>
                            <button type="button" class="meeting-close" onclick="closeMeetingDropdown()" aria-label="Close session requests">×</button>
                        </div>
                        <div id="meetingRequestsList" class="max-h-72 overflow-y-auto divide-y divide-gray-100">
                            <div class="meeting-empty" id="noRequestsMsg">No pending requests</div>
                        </div>
                        <div class="meeting-dropdown-footer">
                            <span id="lastCheckedLabel">Checking requests…</span>
                            <a href="<?= base_url('admin/sessions') ?>">View all sessions</a>
                        </div>
                    </div>
                </div>
