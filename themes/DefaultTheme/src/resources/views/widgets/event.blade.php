@push('styles')
    <style>
        .event-widget {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            transition: all 0.3s ease-in-out;
        }

        .event-widget:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.07);
        }

        .icon-box {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            border-radius: 0.75rem;
        }

        .event-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .fade-in-event {
            animation: fadeInUp 0.4s ease both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush
@php
    $variables = get_widget($widget);
    $events = $variables['events'];
    $today = $variables['today_formatted'];
@endphp

<div class="event-widget card border-0 shadow-sm rounded-4 overflow-hidden animate__animated animate__fadeInUp">
    <div class="card-header bg-light bg-gradient px-4 py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <div class="icon-box bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-calendar-week-fill"></i>
            </div>
            <h5 class="fw-semibold mb-0">رویدادهای امروز</h5>
        </div>
        <span class="text-muted small">{{ $today }}</span>
    </div>

    <div class="card-body bg-white px-4 pt-3 pb-4">
        @if (count($events))
            <ul class="list-unstyled mb-0">
                @foreach ($events as $item)
                    <li class="d-flex align-items-start py-2 fade-in-event">
                        <span class="event-dot me-3 mt-1 bg-primary"></span>
                        <span class="text-dark fw-medium">{{ $item['description'] }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-calendar-x fs-3 mb-2 d-block"></i>
                <div>امروز رویدادی ثبت نشده است.</div>
            </div>
        @endif
    </div>
</div>




@push('scripts')
    <script>
        document.querySelectorAll('.btn-info-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                let icon = this.querySelector('i');
                icon.classList.toggle('bi-chevron-down');
                icon.classList.toggle('bi-chevron-up');

            });
        });
    </script>
@endpush
