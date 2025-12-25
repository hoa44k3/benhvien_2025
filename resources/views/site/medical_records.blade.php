@extends('site.master')

@section('title','Hồ sơ bệnh án')

@section('body')
    {{-- HEADER PROFILE --}}
    <div class="bg-white border-b border-slate-200 pt-10 pb-20">
        <div class="container mx-auto max-w-6xl px-4">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="relative group">
                    <div class="w-24 h-24 bg-gradient-to-tr from-primary to-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-sky-200 transform group-hover:scale-105 transition duration-300">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-500 border-4 border-white rounded-full"></div>
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $user->name }}</h1>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm text-slate-500">
                        <span class="flex items-center px-3 py-1 bg-slate-50 rounded-full border border-slate-100"><i class="fas fa-phone mr-2 text-primary"></i> {{ $user->phone ?? '---' }}</span>
                        <span class="flex items-center px-3 py-1 bg-slate-50 rounded-full border border-slate-100"><i class="fas fa-envelope mr-2 text-primary"></i> {{ $user->email }}</span>
                        <span class="flex items-center px-3 py-1 bg-slate-50 rounded-full border border-slate-100"><i class="fas fa-id-card mr-2 text-primary"></i> Mã BN: #{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto max-w-6xl px-4 -mt-10 pb-20">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 min-h-[600px] flex flex-col md:flex-row overflow-hidden">
            
            {{-- SIDEBAR TABS --}}
            <div class="w-full md:w-64 bg-slate-50 border-b md:border-b-0 md:border-r border-slate-200 p-4 md:p-6 flex-shrink-0">
                <nav class="flex md:flex-col gap-2 overflow-x-auto md:overflow-visible pb-2 md:pb-0 scrollbar-hide">
                    <button class="tab-item active flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl text-left transition-all w-full whitespace-nowrap group" data-tab="medical-records">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center group-[.active]:bg-blue-600 group-[.active]:text-white transition-colors">
                            <i class="fas fa-file-medical-alt"></i>
                        </div>
                        Bệnh án <span class="ml-auto bg-white px-2 py-0.5 rounded-md text-xs border border-slate-200 text-slate-500 font-bold shadow-sm">{{ $medicalRecords->count() }}</span>
                    </button>
                    
                    <button class="tab-item flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl text-left text-slate-600 hover:bg-white hover:shadow-sm transition-all w-full whitespace-nowrap group" data-tab="prescriptions">
                        <div class="w-8 h-8 rounded-lg bg-slate-200 text-slate-500 flex items-center justify-center group-[.active]:bg-emerald-500 group-[.active]:text-white transition-colors">
                            <i class="fas fa-pills"></i>
                        </div>
                        Đơn thuốc <span class="ml-auto bg-white px-2 py-0.5 rounded-md text-xs border border-slate-200 text-slate-500 font-bold shadow-sm">{{ $prescriptions->count() }}</span>
                    </button>
                </nav>
            </div>

            {{-- CONTENT AREA --}}
            <div class="flex-grow p-6 md:p-8 bg-white">
                
                {{-- TAB 1: BỆNH ÁN --}}
                <div id="medical-records" class="tab-content active space-y-6 animate-fade-in-up">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <h3 class="text-xl font-bold text-slate-800">Lịch sử khám bệnh</h3>
                        <div class="text-xs text-slate-400">Hiển thị {{ $medicalRecords->count() }} hồ sơ</div>
                    </div>

                    @if($medicalRecords->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 border-2 border-dashed border-slate-100 rounded-2xl bg-slate-50">
                            <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                <i class="fas fa-folder-open text-2xl"></i>
                            </div>
                            <p class="text-slate-500 font-medium">Chưa có hồ sơ bệnh án nào.</p>
                            <a href="{{ route('schedule') }}" class="mt-4 px-4 py-2 bg-white border border-slate-200 text-primary text-sm font-bold rounded-lg hover:bg-slate-50 transition">Đặt lịch khám ngay</a>
                        </div>
                    @endif

                    @foreach($medicalRecords as $record)
                    <div class="border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition duration-300 bg-white group relative overflow-hidden">
                        {{-- Decorative --}}
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-500"></div>

                        {{-- Header Card --}}
                        <div class="flex flex-wrap justify-between items-start gap-4 mb-5">
                            <div>
                                <h4 class="font-bold text-lg text-slate-800 mb-1 group-hover:text-primary transition">{{ $record->title }}</h4>
                                <div class="text-sm text-slate-500 flex flex-wrap items-center gap-x-4 gap-y-2">
                                    <span class="flex items-center"><i class="far fa-calendar-alt mr-1.5 text-slate-400"></i> {{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</span>
                                    <span class="flex items-center"><i class="far fa-clock mr-1.5 text-slate-400"></i> {{ \Carbon\Carbon::parse($record->date)->format('H:i') }}</span>
                                    <span class="flex items-center text-slate-600 font-medium bg-slate-50 px-2 py-0.5 rounded"><i class="fas fa-user-md mr-1.5 text-primary"></i> BS. {{ $record->doctor->user->name ?? ($record->doctor->name ?? '--') }}</span>
                                </div>
                            </div>
                            
                            @php
                                $statusClass = match(strtolower($record->status)) {
                                    'completed' => 'bg-green-100 text-green-700 border-green-200',
                                    'hủy' => 'bg-red-100 text-red-700 border-red-200',
                                    'đang_khám' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    default => 'bg-slate-100 text-slate-600 border-slate-200'
                                };
                                $statusLabel = match(strtolower($record->status)) {
                                    'completed' => 'Đã hoàn thành',
                                    'hủy' => 'Đã hủy',
                                    'đang_khám' => 'Đang khám',
                                    default => 'Chờ khám'
                                };
                            @endphp
                            <span class="{{ $statusClass }} border px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        {{-- Diagnosis & Treatment --}}
                        <div class="grid md:grid-cols-2 gap-4 text-sm mb-5">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <span class="flex items-center text-xs font-bold text-slate-400 uppercase mb-2 tracking-wide">
                                    <i class="fas fa-stethoscope mr-1.5"></i> Chẩn đoán
                                </span>
                                <p class="text-slate-800 font-medium leading-relaxed">{{ $record->diagnosis ?? 'Đang cập nhật...' }}</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <span class="flex items-center text-xs font-bold text-slate-400 uppercase mb-2 tracking-wide">
                                    <i class="fas fa-prescription mr-1.5"></i> Hướng điều trị
                                </span>
                                <p class="text-slate-800 font-medium leading-relaxed">{{ $record->treatment ?? 'Đang cập nhật...' }}</p>
                            </div>
                        </div>

                        {{-- REVIEW SECTION --}}
                        @if($record->status == 'completed' || $record->diagnosis)
                            <div class="border-t border-slate-100 pt-4 mt-2">
                                @if($record->review)
                                    {{-- ĐÃ ĐÁNH GIÁ: Hiện đầy đủ sao và lời bình --}}
                                    <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-100 relative">
                                        <div class="absolute -top-2 left-6 w-4 h-4 bg-yellow-50 border-t border-l border-yellow-100 transform rotate-45"></div>
                                        
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-xs font-bold text-yellow-700 uppercase tracking-wide flex items-center gap-1">
                                                <i class="fas fa-star-half-alt"></i> Đánh giá của bạn
                                            </span>
                                            <div class="flex text-yellow-400 text-xs gap-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $record->review->rating ? '' : 'text-slate-200' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        
                                        @if($record->review->comment)
                                            <p class="text-sm text-slate-700 italic border-l-2 border-yellow-300 pl-3">
                                                "{{ $record->review->comment }}"
                                            </p>
                                        @else
                                            <p class="text-xs text-slate-400 italic">Không có lời bình.</p>
                                        @endif
                                    </div>
                                @else
                                    {{-- CHƯA ĐÁNH GIÁ: Hiện nút --}}
                                    <div class="flex justify-end">
                                        <button onclick="openReviewModal({{ $record->doctor_id }}, {{ $record->id }}, '{{ $record->doctor->user->name ?? 'Bác sĩ' }}')" 
                                                class="px-4 py-2 bg-white border border-yellow-400 text-yellow-600 text-sm font-semibold rounded-lg hover:bg-yellow-50 shadow-sm transition-all flex items-center gap-2 group/btn">
                                            <i class="far fa-star group-hover/btn:rotate-180 transition-transform duration-300"></i> Viết đánh giá
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- TAB 2: ĐƠN THUỐC --}}
                <div id="prescriptions" class="tab-content hidden space-y-6 animate-fade-in-up">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <h3 class="text-xl font-bold text-slate-800">Đơn thuốc của bạn</h3>
                    </div>
                    
                    @if($prescriptions->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 border-2 border-dashed border-slate-100 rounded-2xl bg-slate-50">
                            <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                <i class="fas fa-pills text-2xl"></i>
                            </div>
                            <p class="text-slate-500 font-medium">Chưa có đơn thuốc nào.</p>
                        </div>
                    @endif

                    @foreach ($prescriptions as $prescription)
                        <div class="border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg transition duration-300 bg-white">
                            <div class="bg-emerald-50/50 px-6 py-4 flex justify-between items-center border-b border-emerald-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-emerald-600 shadow-sm">
                                        <i class="fas fa-prescription"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm">Mã đơn: #{{ $prescription->code }}</div>
                                        <div class="text-xs text-slate-500">{{ $prescription->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                <span class="bg-white px-3 py-1 rounded-full text-xs font-bold text-slate-600 border border-slate-100 shadow-sm">
                                    BS. {{ $prescription->doctor->user->name ?? ($prescription->doctor->name ?? '---') }}
                                </span>
                            </div>
                            <div class="p-6">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="text-xs text-slate-400 uppercase bg-slate-50/50 border-y border-slate-100">
                                            <tr>
                                                <th class="py-3 px-4 text-left font-semibold">Tên thuốc</th>
                                                <th class="py-3 px-4 text-center font-semibold">Số lượng</th>
                                                <th class="py-3 px-4 text-left font-semibold">Hướng dẫn sử dụng</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            @foreach ($prescription->items as $item)
                                                <tr class="hover:bg-slate-50/30 transition">
                                                    <td class="py-3 px-4 font-bold text-slate-700">{{ $item->medicine_name }}</td>
                                                    <td class="py-3 px-4 text-center">
                                                        <span class="bg-slate-100 px-2 py-1 rounded text-slate-600 font-mono text-xs">{{ $item->quantity }}</span>
                                                    </td>
                                                    <td class="py-3 px-4 text-slate-600 leading-relaxed">{{ $item->instruction ?? $item->dosage }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if ($prescription->note)
                                    <div class="mt-5 text-sm bg-yellow-50 text-yellow-800 p-4 rounded-xl border border-yellow-100 flex items-start gap-3">
                                        <i class="fas fa-info-circle mt-0.5 text-yellow-600"></i>
                                        <div>
                                            <strong class="block text-xs uppercase opacity-70 mb-1">Lời dặn của bác sĩ:</strong>
                                            {{ $prescription->note }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div> 

            </div>
        </div>
    </div>

    {{-- MODAL ĐÁNH GIÁ (Improved Design) --}}
    <div id="reviewModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeReviewModal()"></div>
        
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative z-10 p-0 transform transition-all scale-100 overflow-hidden animate-fade-in-up">
                
                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-primary to-blue-600 p-6 text-center relative">
                    <button onclick="closeReviewModal()" class="absolute top-4 right-4 text-white/70 hover:text-white transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg text-yellow-400 text-3xl">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Đánh giá dịch vụ</h3>
                    <p class="text-blue-100 text-sm mt-1">Ý kiến của bạn giúp chúng tôi cải thiện tốt hơn</p>
                </div>

                <div class="p-6">
                    <p class="text-sm text-slate-500 text-center mb-6">Bạn đang đánh giá bác sĩ: <br> <span id="reviewDoctorName" class="font-bold text-lg text-slate-800 block mt-1"></span></p>

                    <form action="{{ route('review.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="doctor_id" id="reviewDoctorId">
                        <input type="hidden" name="medical_record_id" id="reviewMedicalRecordId">
                        
                        {{-- Stars Rating --}}
                        <div class="mb-6 flex justify-center">
                            <div class="flex flex-row-reverse gap-1 group/stars">
                                @for($i=5; $i>=1; $i--)
                                    <input type="radio" name="rating" id="star{{$i}}" value="{{$i}}" class="peer hidden" {{$i==5?'checked':''}} />
                                    <label for="star{{$i}}" class="text-4xl text-slate-200 cursor-pointer peer-checked:text-yellow-400 hover:text-yellow-400 peer-hover:text-yellow-400 transition-colors transform hover:scale-110">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Lời nhận xét của bạn</label>
                            <textarea name="comment" class="w-full bg-slate-50 border border-slate-200 p-4 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition text-sm text-slate-700 placeholder-slate-400" rows="3" placeholder="Bác sĩ rất tận tâm, chuyên nghiệp..."></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="closeReviewModal()" class="px-4 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">Đóng</button>
                            <button type="submit" class="px-4 py-3 bg-gradient-to-r from-primary to-blue-600 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-blue-200 transition transform active:scale-95">Gửi đánh giá</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tab-item.active div { background-color: #0ea5e9; color: white; }
        .tab-item.active { background-color: #f0f9ff; color: #0284c7; }
    </style>

    <script>
        function openReviewModal(doctorId, recordId, doctorName) {
            document.getElementById('reviewDoctorId').value = doctorId;
            document.getElementById('reviewMedicalRecordId').value = recordId;
            document.getElementById('reviewDoctorName').innerText = doctorName;
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.tab-item');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.add('hidden'));

                    tab.classList.add('active');
                    const targetId = tab.getAttribute('data-tab');
                    const target = document.getElementById(targetId);
                    target.classList.remove('hidden');
                    
                    // Simple animation reset
                    target.classList.remove('animate-fade-in-up');
                    void target.offsetWidth; // trigger reflow
                    target.classList.add('animate-fade-in-up');
                });
            });
        });
    </script>
@endsection