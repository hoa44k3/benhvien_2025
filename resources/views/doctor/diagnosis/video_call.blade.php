@extends('doctor.master')

@section('title', 'Phòng tư vấn trực tuyến')

@section('body')
<div class="container mx-auto max-w-7xl px-4 py-6 h-screen flex flex-col">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="w-3 h-3 bg-red-500 rounded-full animate-ping mr-2"></span>
                Đang gọi cho: {{ $appointment->patient_name }}
            </h2>
            <p class="text-gray-500 text-sm">Mã lịch hẹn: {{ $appointment->code }}</p>
        </div>
        
        <a href="{{ route('doctor.diagnosis.show', $appointment->id) }}" 
           class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-bold transition">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại phòng khám
        </a>
    </div>

    {{-- KHUNG VIDEO JITSI MEET --}}
    <div id="meet" class="flex-1 w-full bg-black rounded-xl shadow-2xl overflow-hidden relative">
        <div class="absolute inset-0 flex items-center justify-center text-white">
            <i class="fas fa-circle-notch fa-spin text-4xl"></i>
            <span class="ml-3">Đang kết nối đến máy chủ video...</span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Nhúng thư viện Jitsi --}}
<script src='https://meet.jit.si/external_api.js'></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const domain = "meet.jit.si";
        const options = {
            roomName: "{{ $roomName }}", // Tên phòng (Unique)
            width: "100%",
            height: "100%",
            parentNode: document.querySelector('#meet'),
            userInfo: {
                email: "{{ $userEmail }}",
                displayName: "Bác sĩ: {{ $userName }}"
            },
            configOverwrite: { 
                startWithAudioMuted: false,
                startWithVideoMuted: false,
                prejoinPageEnabled: false // Vào thẳng luôn không cần chờ
            },
            interfaceConfigOverwrite: {
                TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                    'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                    'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                    'videoquality', 'filmstrip', 'invite', 'feedback', 'stats', 'shortcuts',
                    'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone',
                    'security'
                ],
            }
        };
        
        // Khởi tạo API Jitsi
        const api = new JitsiMeetExternalAPI(domain, options);

        // Sự kiện khi kết thúc cuộc gọi -> Quay lại trang khám
        api.addEventListeners({
            videoConferenceLeft: function () {
                window.location.href = "{{ route('doctor.diagnosis.show', $appointment->id) }}";
            }
        });
    });
</script>
@endsection