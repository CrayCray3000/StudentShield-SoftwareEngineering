<!-- FAISS Trajectory Student Profile Modal -->
<style>
    .modal-backdrop {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
</style>
<div id="studentProfileModal" class="fixed inset-0 z-[100] hidden items-center justify-center modal-backdrop">
    <div class="bg-[#FAFDFB] rounded-2xl p-0 w-[95%] max-w-4xl shadow-[0_20px_70px_-10px_rgba(0,0,0,0.5)] flex flex-col max-h-[90vh] overflow-hidden border border-gray-200">
        
        <!-- Header Profile Info -->
        <div class="bg-gradient-to-r from-[#166534] to-[#14532D] text-white px-8 py-6 flex shrink-0 justify-between items-start border-b-4 border-[#EAB308]">
            <div class="flex gap-4 items-center">
                <div class="h-16 w-16 bg-white/10 rounded-full flex items-center justify-center border-2 border-white/20 text-2xl font-bold font-heading shadow-inner">
                    <span id="spInitials">ST</span>
                </div>
                <div>
                    <h2 class="text-2xl font-heading font-bold flex items-center gap-2">
                        <span id="spName">Loading...</span>
                        <span id="spCriticalBadge" class="hidden px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-red-500/20 text-red-100 border border-red-500/30">High Risk Pattern</span>
                    </h2>
                    <div class="flex flex-wrap gap-4 mt-2 text-white/80 text-sm font-medium">
                        <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-[#EAB308]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg> <span id="spId">ID</span></span>
                        <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-[#EAB308]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg> <span id="spSection">Section</span></span>
                    </div>
                </div>
            </div>
            <button onclick="closeStudentProfile()" class="p-2 bg-white/10 hover:bg-white/20 rounded-xl transition-colors text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-8 bg-gray-50/30">
            <!-- Parent Info Mini-card -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="dashboard-card p-4 rounded-xl shadow-sm border border-gray-200/60 bg-white">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Parent/Guardian Email</p>
                    <p class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> 
                        <span id="spParentEmail">N/A</span>
                    </p>
                </div>
                <div class="dashboard-card p-4 rounded-xl shadow-sm border border-gray-200/60 bg-white">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Parent/Guardian Phone</p>
                    <p class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span id="spParentPhone">N/A</span>
                    </p>
                </div>
            </div>

            <!-- FAISS Trajectory Tracker -->
            <div class="dashboard-card bg-white p-5 rounded-2xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                    <svg class="w-16 h-16 text-[#0B5E2E]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/></svg>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-bold font-heading text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#EAB308]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        FAISS Behavioral Trajectory
                    </h3>
                    <p class="text-xs text-gray-500 font-medium">14-Day Rolling Machine Learning Risk Graph</p>
                </div>
                <!-- Chart Container -->
                <div id="studentRiskChart" class="w-full h-[280px]"></div>
            </div>

            <!-- History Feed: Vents & Incidents -->
            <div>
                <h3 class="text-md font-bold font-heading text-gray-900 mb-4 border-b border-gray-200 pb-2">Chronological History</h3>
                <div id="spHistoryFeed" class="space-y-4">
                    <!-- Javascript will populate timeline here -->
                    <div class="text-center py-6 text-gray-400 text-sm">Loading history...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ApexCharts already loaded in parent dashboard, do not reload -->
<script>
    let riskChart = null;

    function openStudentProfile(userId) {
        document.getElementById('studentProfileModal').classList.remove('hidden');
        document.getElementById('studentProfileModal').classList.add('flex');
        
        // Provide a premium skeleton loading experience
        document.getElementById('spInitials').innerHTML = '<div class="h-8 w-8 bg-white/20 rounded-full animate-pulse"></div>';
        document.getElementById('spName').innerHTML = '<div class="h-8 bg-white/20 rounded w-48 animate-pulse"></div>';
        document.getElementById('spId').innerHTML = '<div class="h-4 bg-white/20 rounded w-20 animate-pulse"></div>';
        document.getElementById('spSection').innerHTML = '<div class="h-4 bg-white/20 rounded w-24 animate-pulse"></div>';
        document.getElementById('spParentEmail').innerHTML = '<div class="h-5 bg-gray-200 rounded w-40 animate-pulse"></div>';
        document.getElementById('spParentPhone').innerHTML = '<div class="h-5 bg-gray-200 rounded w-32 animate-pulse"></div>';
        
        // Skeleton for the history feed (3 fake cards)
        document.getElementById('spHistoryFeed').innerHTML = `
            <div class="space-y-4">
                <div class="h-24 bg-gray-100 rounded-xl w-full animate-pulse border border-gray-200"></div>
                <div class="h-24 bg-gray-100 rounded-xl w-full animate-pulse border border-gray-200"></div>
            </div>
        `;
        
        // Skeleton for the FAISS chart
        document.getElementById('studentRiskChart').innerHTML = '<div class="h-full w-full bg-gray-100 rounded-xl animate-pulse border border-gray-200 flex items-center justify-center text-gray-400 font-medium text-sm">Building Neural Graph...</div>';
        
        document.getElementById('spCriticalBadge').classList.add('hidden');

        const PROFILE_API_BASE = "<?= base_url('admin/api/student-profile') ?>";
        fetch(`${PROFILE_API_BASE}/${userId}`)
            .then(res => {
                if (!res.ok) {
                    return res.text().then(txt => { throw new Error('HTTP ' + res.status + ': ' + txt.substring(0, 200)); });
                }
                return res.json();
            })
            .then(data => {
                const u = data.user;
                document.getElementById('spName').innerText = u.name;
                
                // Get initials
                const names = u.name.split(' ');
                let initials = names[0].charAt(0);
                if (names.length > 1) initials += names[names.length-1].charAt(0);
                document.getElementById('spInitials').innerText = initials.toUpperCase();

                document.getElementById('spId').innerText = u.student_id || 'No ID';
                document.getElementById('spSection').innerText = u.section || 'Unassigned';
                document.getElementById('spParentEmail').innerText = u.parent_email || 'N/A';
                document.getElementById('spParentPhone').innerText = u.parent_phone || 'N/A';

                // Render Chart
                renderFAISSChart(data.timeline);
                
                // Check if last two vents mark a critical rising pattern
                if(data.timeline.length >= 2) {
                    const last = data.timeline[data.timeline.length-1].y;
                    const prev = data.timeline[data.timeline.length-2].y;
                    if(last >= 75 && prev >= 50) {
                        document.getElementById('spCriticalBadge').classList.remove('hidden');
                    }
                }

                // Render Feed
                renderHistoryFeed(data.vents, data.incidents);
            })
            .catch(err => {
                console.error('Profile API Error:', err);
                document.getElementById('spHistoryFeed').innerHTML = '<div class="text-center py-6 text-red-500 text-xs font-mono break-all px-4">' + err.message + '</div>';
            });
    }

    function closeStudentProfile() {
        document.getElementById('studentProfileModal').classList.add('hidden');
        document.getElementById('studentProfileModal').classList.remove('flex');
        if(riskChart) riskChart.destroy();
    }

    function renderFAISSChart(timelineData) {
        // Destroy any previous chart instance to prevent memory leaks
        if(riskChart) {
            try { riskChart.destroy(); } catch(e) {}
            riskChart = null;
        }

        const chartContainer = document.getElementById('studentRiskChart');

        if(!timelineData || timelineData.length === 0) {
            chartContainer.innerHTML = '<div class="w-full h-full flex items-center justify-center text-gray-400 text-sm font-medium">No sufficient vent data to plot behavioral trajectory.</div>';
            return;
        }

        // CRITICAL: Clear all existing content before ApexCharts render.
        // ApexCharts will fail silently or render with 0px height if the
        // container has leftover skeleton/placeholder nodes inside it.
        chartContainer.innerHTML = '';

        var options = {
            series: [{
                name: 'FAISS Calculated Risk %',
                data: timelineData
            }],
            chart: {
                type: 'area',
                height: 280,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            colors: ['#D95A5A'], // Use the custom soft red
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                type: 'category',
                tooltip: { enabled: false },
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#9ca3af', fontSize: '12px' } }
            },
            yaxis: {
                min: 0,
                max: 100,
                tickAmount: 4,
                labels: { 
                    style: { colors: '#9ca3af' },
                    formatter: function(val) {
                        if(val === 100) return 'Critical';
                        if(val === 75) return 'High';
                        if(val === 50) return 'Medium';
                        if(val === 25) return 'Low';
                        return val;
                    }
                }
            },
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val, opts) {
                        const score = opts.w.config.series[opts.seriesIndex].data[opts.dataPointIndex].sentiment;
                        return val + '% Risk | NLP Score: ' + score;
                    }
                }
            },
            annotations: {
                yAxis: [{
                    y: 75,
                    borderColor: '#C24545',
                    label: {
                        borderColor: '#C24545',
                        style: { color: '#fff', background: '#C24545', fontSize: '10px', fontWeight: 'bold' },
                        text: 'DANGER THRESHOLD'
                    }
                }]
            }
        };

        try {
            riskChart = new ApexCharts(chartContainer, options);
            riskChart.render();
        } catch(e) {
            console.error('FAISS Chart render error:', e);
            chartContainer.innerHTML = '<div class="w-full h-full flex items-center justify-center text-red-400 text-sm font-medium">Chart rendering failed. Please refresh.</div>';
        }
    }

    function renderHistoryFeed(vents, incidents) {
        const feed = document.getElementById('spHistoryFeed');
        feed.innerHTML = '';
        
        let allItems = [];
        
        // Normalize Vents
        vents.forEach(v => {
            allItems.push({
                type: 'vent',
                date: new Date(v.created_at),
                raw_date: v.created_at,
                data: v
            });
        });

        // Normalize Incidents
        incidents.forEach(i => {
            allItems.push({
                type: 'incident',
                date: new Date(i.created_at),
                raw_date: i.created_at,
                data: i
            });
        });

        // Sort completely descending
        allItems.sort((a,b) => b.date - a.date);

        if(allItems.length === 0) {
            feed.innerHTML = '<div class="text-center py-6 text-gray-400 text-sm">No recorded history.</div>';
            return;
        }

        let html = '';
        allItems.forEach(item => {
            const dateStr = new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true }).format(item.date);
            
            if(item.type === 'vent') {
                const v = item.data;
                const rl = (v.risk_level || 'LOW').toUpperCase();
                const riskColor = rl === 'CRITICAL' ? 'bg-red-50 text-red-700 border-red-200' : 
                                rl === 'HIGH' ? 'bg-orange-50 text-orange-700 border-orange-200' : 
                                rl === 'MEDIUM' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-green-50 text-green-700 border-green-200';
                                
                const dotColor = rl === 'CRITICAL' ? 'bg-red-500' : 
                                rl === 'HIGH' ? 'bg-orange-500' : 
                                rl === 'MEDIUM' ? 'bg-yellow-500' : 'bg-green-500';

                html += `
                <div class="relative pl-6 pb-6 border-l-2 border-gray-100 last:border-0 last:pb-0">
                    <div class="absolute -left-[5px] top-1 w-2 h-2 rounded-full ${dotColor} ring-4 ring-white"></div>
                    <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm hovered:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border ${riskColor}">Internal Vent - ${rl} Risk</span>
                            <span class="text-xs text-gray-400 font-medium">${dateStr}</span>
                        </div>
                        <p class="text-sm text-gray-700 line-clamp-3">"${v.raw_text}"</p>
                        <div class="mt-3 flex items-center justify-between border-t border-gray-50 pt-2">
                            <span class="text-[10px] text-gray-400">FAISS Risk Score: ${v.risk_percent || '0'}%</span>
                            <span class="text-[10px] uppercase font-bold tracking-wider ${v.status === 'Resolved' ? 'text-emerald-600' : 'text-amber-500'}">
                                ${v.status === 'Resolved' ? '✓ Resolved' : '• Pending Review'}
                            </span>
                        </div>
                    </div>
                </div>`;
            } else {
                const i = item.data;
                html += `
                <div class="relative pl-6 pb-6 border-l-2 border-gray-100 last:border-0 last:pb-0">
                    <div class="absolute -left-[6px] top-1 w-2.5 h-2.5 rounded-full bg-blue-500 ring-4 ring-white"></div>
                    <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100 shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200">Incident Associated</span>
                            <span class="text-xs text-gray-500 font-medium">${dateStr}</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 mb-1">Bullying Report</p>
                        <p class="text-xs text-gray-600">This student was marked as involved in this reported incident.</p>
                        <div class="mt-3 flex justify-end">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-blue-600">ID: #${i.id} - ${i.status.replace('_', ' ')}</span>
                        </div>
                    </div>
                </div>`;
            }
        });

        feed.innerHTML = html;
    }
</script>
