<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tư vấn trực tuyến - SmartHospital</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Sử dụng Tailwind CSS trực tiếp cho nhẹ --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Đảm bảo full màn hình tuyệt đối */
        html, body { height: 100%; margin: 0; overflow: hidden; background-color: #000; }
        
        #video-container {
            height: calc(100% - 70px); /* Trừ đi header */
            width: 100%;
            position: relative;
        }

        /* Hiệu ứng loading */
        .loading-overlay {
            position: absolute; inset: 0; background: #000; z-index: 5;
            display: flex; align-items: center; justify-content: center; color: white;
        }
    </style>
</head>
<body class="bg-gray-900 text-white flex flex-col h-screen">

    {{-- HEADER RIÊNG BIỆT (Không dính dáng đến Admin Layout) --}}
    <div class="h-[70px] bg-gray-800 border-b border-gray-700 flex justify-between items-center px-6 shadow-lg z-50">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center font-bold">
                BS
            </div>
            <div>
                <h1 class="font-bold text-lg flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    {{ $appointment->patient_name ?? 'Bệnh nhân' }}
                </h1>
                <p class="text-xs text-gray-400">Mã: {{ $appointment->code }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            {{-- Nút quay lại an toàn --}}
            <a href="{{ route('doctor.diagnosis.show', $appointment->id) }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-sm font-medium transition">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
            
            {{-- Nút kết thúc --}}
            <button id="hangup-btn" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg transition transform active:scale-95 flex items-center">
                <i class="fas fa-phone-slash mr-2"></i> Kết thúc
            </button>
        </div>
    </div>

    {{-- KHUNG VIDEO --}}
    <div id="video-container">
        <div id="loading" class="loading-overlay">
            <div class="text-center">
                <i class="fas fa-circle-notch fa-spin text-4xl mb-3 text-blue-500"></i>
                <p>Đang kết nối đến phòng khám...</p>
            </div>
        </div>
        <div id="meet" class="w-full h-full"></div>
    </div>

    {{-- SCRIPT XỬ LÝ --}}
    <script src='https://meet.jit.si/external_api.js'></script>
    <script>
        // Cấu hình dữ liệu an toàn từ Laravel
        const CONFIG = {
            apptId: @json($appointment->id),
            doctorId: @json($appointment->doctor_id),
            patientId: @json($appointment->user_id),
            roomName: @json($roomName),
            userEmail: @json($userEmail),
            userName: @json($userName),
            redirectUrl: "{{ route('doctor.diagnosis.show', $appointment->id) }}",
            apiStart: "{{ route('api.call.start') }}",
            apiEnd: "{{ route('api.call.end') }}",
            csrf: "{{ csrf_token() }}"
        };

        document.addEventListener("DOMContentLoaded", function() {
            // Kiểm tra thư viện
            if (typeof JitsiMeetExternalAPI === 'undefined') {
                alert("Lỗi: Không tải được Jitsi. Vui lòng kiểm tra mạng.");
                return;
            }

            const domain = "meet.jit.si";
            const options = {
                roomName: CONFIG.roomName,
                width: "100%",
                height: "100%",
                parentNode: document.querySelector('#meet'),
                userInfo: {
                    email: CONFIG.userEmail,
                    displayName: "BS: " + CONFIG.userName
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
                },
                onload: function() {
                    // Tắt loading khi iframe tải xong
                    document.getElementById('loading').style.display = 'none';
                }
            };

            // Khởi tạo Jitsi
            let api = null;
            let callDbId = null;

            try {
                api = new JitsiMeetExternalAPI(domain, options);
            } catch (error) {
                console.error("Lỗi khởi tạo:", error);
            }

            if (api) {
                // 1. Sự kiện vào phòng -> Gọi API Start
                api.addEventListeners({
                    videoConferenceJoined: function() {
                        console.log("Đã vào phòng!");
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
                        .catch(e => console.error(e));
                    },

                    // 2. Sự kiện tắt máy trong khung video
                    videoConferenceLeft: function() {
                        handleEndCall();
                    }
                });
            }

            // 3. Xử lý nút Kết thúc trên Header
            document.getElementById('hangup-btn').addEventListener('click', function() {
                if(api) api.dispose();
                handleEndCall();
            });

            // Hàm xử lý kết thúc chung
            function handleEndCall() {
                // Chuẩn bị dữ liệu: Luôn gửi appointment_id để làm chìa khóa xóa phòng
                const payload = {
                    call_id: callDbId, 
                    appointment_id: CONFIG.apptId, // <--- Quan trọng: Gửi thêm cái này
                    _token: CONFIG.csrf
                };

                //  LUÔN GỌI API (Bất chấp có ID cuộc gọi hay không)
                fetch(CONFIG.apiEnd, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': CONFIG.csrf 
                    },
                    body: JSON.stringify(payload)
                })
                .catch(err => console.error("Lỗi API:", err)) // Nếu lỗi mạng thì kệ, cứ chạy tiếp
                .finally(() => {
                    // Xong xuôi thì mới chuyển trang
                    window.location.href = CONFIG.redirectUrl;
                });
            }
        });
    </script>
</body>
</html>