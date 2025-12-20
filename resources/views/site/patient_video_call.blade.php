@extends('site.master')

@section('title', 'Tư vấn trực tuyến')

{{-- CSS để Video hiển thị Full màn hình --}}
@section('styles')
<style>
    /* 1. Đảm bảo body full chiều cao */
    html, body { height: 100%; margin: 0; overflow: hidden; }
    
    /* 2. Ẩn header/footer của trang web chính để tập trung gọi */
    header, footer, .fixed-bottom { display: none !important; }

    /* 3. Container video */
    .video-container { 
        height: 100vh; /* Full chiều cao màn hình */
        width: 100%; 
        position: relative; 
        background-color: #000; 
        display: flex;
        flex-direction: column;
    }
    
    #meet { flex: 1; width: 100%; }
</style>
@endsection

@section('body')
<div class="video-container">
    {{-- Header nhỏ hiển thị thông tin trong cuộc gọi --}}
    <div class="flex justify-between items-center px-6 py-3 bg-gray-900 text-white shadow-md z-10" style="height: 60px;">
        <div>
            <h2 class="text-lg font-bold flex items-center">
                <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse mr-2"></span>
                BS. {{ $appointment->doctor->name ?? 'Bác sĩ' }}
            </h2>
        </div>
        
        {{-- Nút kết thúc --}}
        <button id="hangup-btn" class="bg-red-600 hover:bg-red-700 text-white px-5 py-1.5 rounded-lg font-bold transition flex items-center text-sm">
            <i class="fas fa-phone-slash mr-2"></i> Kết thúc
        </button>
    </div>

    {{-- KHUNG VIDEO JITSI --}}
    <div id="meet"></div>
</div>

{{-- CẤU HÌNH DỮ LIỆU TỪ LARAVEL --}}
<script>
    const CONFIG = {
        apptId: {{ $appointment->id }},
        doctorId: {{ $appointment->doctor_id }},
        patientId: {{ $appointment->user_id }},
        roomName: "{{ $roomName }}",
        userEmail: "{{ $userEmail }}",
        userName: "{{ $userName }}",
        // Sau khi tắt máy -> Quay về trang Lịch khám
        redirectUrl: "{{ route('schedule') }}", 
        apiStart: "{{ route('api.call.start') }}",
        apiEnd: "{{ route('api.call.end') }}",
        csrf: "{{ csrf_token() }}"
    };
</script>
@endsection

@section('scripts')
<script src='https://meet.jit.si/external_api.js'></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const domain = "meet.jit.si";
        const options = {
            roomName: CONFIG.roomName,
            width: "100%",
            height: "100%",
            parentNode: document.querySelector('#meet'),
            userInfo: {
                email: CONFIG.userEmail,
                displayName: "BN: " + CONFIG.userName
            },
            configOverwrite: { 
                startWithAudioMuted: false, 
                startWithVideoMuted: false,
                disableDeepLinking: true, 
                prejoinPageEnabled: false 
            },
            interfaceConfigOverwrite: {
                SHOW_JITSI_WATERMARK: false,
                TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'desktop', 'fullscreen',
                    'hangup', 'chat', 'raisehand', 'tileview', 'videoquality'
                ]
            }
        };

        // Khởi tạo Jitsi
        const api = new JitsiMeetExternalAPI(domain, options);
        let callDbId = null; 

        // --- XỬ LÝ SỰ KIỆN ---

        // 1. Khi vào phòng -> Gọi API Start
        api.addEventListeners({
            videoConferenceJoined: function () {
                fetch(CONFIG.apiStart, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CONFIG.csrf },
                    body: JSON.stringify({ 
                        appointment_id: CONFIG.apptId,
                        doctor_id: CONFIG.doctorId,
                        patient_id: CONFIG.patientId
                    })
                })
                .then(res => res.json())
                .then(data => { callDbId = data.call_id; })
                .catch(err => console.error("Lỗi Start Call:", err));
            },

            // 2. Khi cúp máy (Nút đỏ trong Jitsi)
            videoConferenceLeft: function () {
                endCallAndRedirect();
            }
        });

        // 3. Nút Kết thúc tùy chỉnh
        const hangupBtn = document.getElementById('hangup-btn');
        if(hangupBtn) {
            hangupBtn.addEventListener('click', () => {
                api.dispose();
                endCallAndRedirect();
            });
        }

        function endCallAndRedirect() {
            if(callDbId) {
                fetch(CONFIG.apiEnd, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CONFIG.csrf },
                    body: JSON.stringify({ call_id: callDbId })
                }).finally(() => {
                    window.location.href = CONFIG.redirectUrl;
                });
            } else {
                window.location.href = CONFIG.redirectUrl;
            }
        }
    });
</script>
@endsection