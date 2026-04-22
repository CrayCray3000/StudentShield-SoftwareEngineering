import os

vent_path = r'd:\\laragon\\www\\student shield\\web-app-ci4\\app\\Views\\vent_form.php'
bully_path = r'd:\\laragon\\www\\student shield\\web-app-ci4\\app\\Views\\reports\\bullying_form.php'

with open(vent_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace the form action
content = content.replace("base_url('student/vent/submit')", "base_url('report-bullying/submit')")
content = content.replace('id="ventForm"', 'id="bullyingForm"')
content = content.replace("<title>StudentShield - Safe Space Portal</title>", "<title>StudentShield - Incident Report</title>")

# Replace Left Panel Text
content = content.replace("It's okay to<br>not be okay.", "Stand Up,<br>Speak Out.")
content = content.replace("Your feelings are valid. This is a judgment-free space where you can express yourself freely and get the support you need.", "This is a safe, completely confidential space to report an incident. Our counseling team is here to protect and support you.")

# Replace Mobile Header text
content = content.replace("How are you feeling today?", "Report an Incident")
content = content.replace("How are you feeling?", "Report an Incident")
content = content.replace("Take your time. There are no wrong answers.", "Your report is strictly confidential.")

# Replace the link back
content = content.replace('''href="<?= base_url('report-bullying') ?>"''', '''href="<?= base_url('student/vent') ?>"''')
content = content.replace("Report Bullying", "Safe Space")
content = content.replace("Report Incident", "Safe Space")

START_MARKER = '<!-- Textarea — grows to fill space -->'
END_MARKER = '<!-- Premium Confidentiality Toggle Card -->'

form_fields = """
                    <!-- Compact Form Layout -->
                    <div class="flex-1 flex flex-col space-y-4">
                        
                        <!-- Role Toggle -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 mb-2.5">I am reporting as a:</p>
                            <input type="hidden" name="role" id="roleInput" value="victim">
                            <div class="flex gap-2">
                                <button type="button" data-role="victim" class="role-btn active px-4 py-2.5 rounded-xl border border-calm-300 bg-calm-50 text-[13px] font-bold text-calm-700 transition-all shadow-[0_2px_10px_rgba(74,155,110,0.12)]">🙋 I am the victim</button>
                                <button type="button" data-role="bystander" class="role-btn px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-[13px] font-semibold text-gray-500 transition-all hover:bg-gray-50">👁️ I am a bystander</button>
                            </div>
                        </div>

                        <!-- Who is involved? (2 Columns) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div id="victimNameBox" class="hidden">
                                <label class="block text-xs font-semibold text-gray-500 mb-2">Who is being bullied?</label>
                                <input type="text" id="victim_name" name="victim_name" class="w-full bg-white/80 border border-gray-200 rounded-xl px-4 py-3 text-[14px] focus:ring-2 focus:ring-calm-200 focus:border-calm-400 outline-none transition-all placeholder-gray-400 font-medium" placeholder="Victim's name">
                            </div>
                            <div id="aggressorNameBox" class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 mb-2">Who is the aggressor? <span class="text-red-400">*</span></label>
                                <input type="text" id="aggressor_name" name="aggressor_name" required class="w-full bg-white/80 border border-gray-200 rounded-xl px-4 py-3 text-[14px] focus:ring-2 focus:ring-calm-200 focus:border-calm-400 outline-none transition-all placeholder-gray-400 font-medium" placeholder="Aggressor's name">
                            </div>
                        </div>

                        <!-- Incident Details -->
                        <div class="flex flex-col flex-1 pb-4 pt-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-2">What happened? <span class="text-red-400">*</span></label>
                            <textarea id="incident_details" name="incident_details" class="w-full flex-1 min-h-[140px] px-5 py-4 textarea-field rounded-2xl outline-none resize-none text-gray-900 placeholder-gray-400/60 leading-[1.6] text-[15px] shadow-sm font-medium" placeholder="Please describe the incident in detail... Where did it happen? When?" required></textarea>
                        </div>
                    </div>
"""

parts = content.split(START_MARKER)
if len(parts) > 1:
    second_half = parts[1].split(END_MARKER)
    if len(second_half) > 1:
        content = parts[0] + form_fields + '<!-- Premium Confidentiality Toggle Card -->' + second_half[1]

# Remove the confidentiality toggle
content = content.split('<!-- Premium Confidentiality Toggle Card -->')[0] + '<!-- Submit -->' + content.split('<!-- Submit -->')[1]

js_logic = """
        // Role Toggle Logic
        document.querySelectorAll('.role-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.role-btn').forEach(b => {
                    b.classList.remove('active', 'bg-calm-50', 'border-calm-300', 'text-calm-700', 'shadow-[0_2px_10px_rgba(74,155,110,0.12)]');
                    b.classList.add('bg-white', 'border-gray-200', 'text-gray-500', 'hover:bg-gray-50');
                });
                
                this.classList.remove('bg-white', 'border-gray-200', 'text-gray-500', 'hover:bg-gray-50');
                this.classList.add('active', 'bg-calm-50', 'border-calm-300', 'text-calm-700', 'shadow-[0_2px_10px_rgba(74,155,110,0.12)]');
                
                const role = this.getAttribute('data-role');
                document.getElementById('roleInput').value = role;
                
                const victimBox = document.getElementById('victimNameBox');
                const aggBox = document.getElementById('aggressorNameBox');
                const victimInput = document.getElementById('victim_name');
                if(role === 'bystander') {
                    victimBox.classList.remove('hidden');
                    aggBox.classList.remove('md:col-span-2');
                    victimInput.required = true;
                } else {
                    victimBox.classList.add('hidden');
                    aggBox.classList.add('md:col-span-2');
                    victimInput.required = false;
                    victimInput.value = '';
                }
            });
        });

        const affirmations = [
            '"You are brave for speaking up."',
            '"Your voice can make a difference."',
            '"We are here to protect you."',
            '"Safety is your fundamental right."'
        ];
        document.getElementById('dailyAffirmation').innerText = affirmations[Math.floor(Math.random() * affirmations.length)];

        document.getElementById('bullyingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const icon = document.getElementById('btnIcon');
            const spinner = document.getElementById('btnSpinner');
            const form = document.getElementById('bullyingForm');

            btn.classList.add('opacity-90', 'cursor-wait');
            text.innerText = 'Encrypting & Sending...';
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
            
            // Map the fields properly for the controller
            // The controller expects: reporting_as, victim_name, bully_name, incident_details
            const formData = new FormData(form);
            const mappedData = new FormData();
            mappedData.append('reporting_as', formData.get('role'));
            mappedData.append('victim_name', formData.get('victim_name') || 'Self');
            mappedData.append('bully_name', formData.get('aggressor_name'));
            mappedData.append('incident_details', formData.get('incident_details'));
            
            // Include CSRF
            mappedData.append(document.querySelector('input[name="csrf_test_name"]').name, document.querySelector('input[name="csrf_test_name"]').value);

            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: mappedData
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'error') {
                    btn.classList.remove('opacity-90', 'cursor-wait');
                    text.innerText = 'Submit Securely';
                    spinner.classList.add('hidden');
                    icon.classList.remove('hidden');
                    
                    const errToast = document.createElement('div');
                    errToast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-red-500 text-white px-6 py-3.5 rounded-xl shadow-xl shadow-red-500/20 text-sm font-semibold flex items-center gap-2.5 z-[110] animate-[slideUp_0.4s_ease-out]';
                    errToast.innerHTML = '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> ' + data.message;
                    document.body.appendChild(errToast);
                    setTimeout(() => { errToast.style.opacity = '0'; errToast.style.transition = 'opacity 0.4s'; setTimeout(() => errToast.remove(), 400); }, 4000);
                    return;
                }

                document.getElementById('incident_details').value = '';
                document.getElementById('aggressor_name').value = '';
                document.getElementById('victim_name').value = '';
                
                btn.classList.remove('opacity-90', 'cursor-wait');
                btn.style.background = 'linear-gradient(135deg, #059669, #10B981)';
                text.innerText = 'Report Submitted ✓';
                spinner.classList.add('hidden');

                const modal = document.getElementById('empathyModal');
                const bd = document.getElementById('empathyBackdrop');
                const ct = document.getElementById('empathyContent');
                
                ct.querySelector('h2').innerText = 'Thank you for standing up.';
                ct.querySelector('p').innerText = "We have securely received your report. A counselor will review this incident immediately.";
                ct.querySelector('span.text-primary').innerText = "You are protected by StudentShield.";
                
                modal.classList.remove('hidden'); modal.classList.add('flex');
                setTimeout(() => { bd.classList.replace('opacity-0','opacity-100'); ct.classList.remove('scale-95','opacity-0'); ct.classList.add('scale-100','opacity-100'); }, 50);
                document.getElementById('closeModalBtn').onclick = () => {
                    window.location.href = '/dashboard';
                };
            })
            .catch(err => {
                console.error('Error:', err);
                text.innerText = 'Submit Securely'; spinner.classList.add('hidden'); icon.classList.remove('hidden');
                btn.classList.remove('opacity-90', 'cursor-wait');
            });
        });
"""

# Replace all script tags from the <script> where variables like textarea are defined to the end
content_parts = content.split('<script>')
first_script = content_parts[1] # tailwind
second_script = content_parts[2] # we replace the second script chunk containing all the interactivity of vent form

new_content = content_parts[0] + '<script>' + first_script + '<script>' + js_logic + '</script></body></html>'

with open(bully_path, 'w', encoding='utf-8') as f:
    f.write(new_content)
