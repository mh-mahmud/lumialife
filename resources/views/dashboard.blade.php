@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/admin-dashboard-static.css') }}">

<div class="lumia-dashboard">
    @if (session('success'))
        <script>Swal.fire({icon:'success',title:'Success',text:@json(session('success')),showConfirmButton:false,timer:2000});</script>
    @endif
    @if (session('error'))
        <script>Swal.fire({icon:'error',title:'Error',text:@json(session('error')),showConfirmButton:false,timer:2000});</script>
    @endif

    <div class="metric-grid">
        @foreach ([
            ['eye','Total visits','1.24','M','+10%','up from','1.12M','mint','up'],
            ['file','Page views','4.08','M','-7%','down from','4.39M','rose','down'],
            ['user','Unique visitors','842','K','~12%','holding around','835K','violet','flat'],
            ['bars','Bounce rate','33','%','steady','matching','33%','blue','flat'],
        ] as $metric)
        <article class="metric-card {{ $metric[7] }}">
            <div class="metric-head">
                <div class="metric-name">
                    <span class="metric-icon">
                        @if($metric[0] === 'eye')
                            <svg viewBox="0 0 24 24"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                        @elseif($metric[0] === 'file')
                            <svg viewBox="0 0 24 24"><path d="M7 3h7l4 4v14H7z"/><path d="M14 3v5h5M10 12h5M10 16h5"/></svg>
                        @elseif($metric[0] === 'user')
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3"/><path d="M6.5 20v-2.5a5.5 5.5 0 0 1 11 0V20"/></svg>
                        @else
                            <svg viewBox="0 0 24 24"><path d="M5 19v-4M10 19V9M15 19V5M20 19V2"/></svg>
                        @endif
                    </span>
                    <span>{{ $metric[1] }}</span>
                </div>
                <span class="trend {{ $metric[8] }}">{{ $metric[8] === 'up' ? '↗' : ($metric[8] === 'down' ? '↘' : '−') }}&nbsp; {{ $metric[4] }}</span>
            </div>
            <div class="metric-value">{{ $metric[2] }}<small>{{ $metric[3] }}</small></div>
            <div class="metric-foot"><b>{{ $metric[8] === 'up' ? '↗' : ($metric[8] === 'down' ? '↘' : '−') }}</b> {{ $metric[5] }} <strong>{{ $metric[6] }}</strong><i></i>last week</div>
        </article>
        @endforeach
    </div>

    <section class="dash-card geography">
        <header class="card-heading">
            <div><span class="eyebrow">GEOGRAPHY</span><h2>Site visits</h2></div>
            <a href="#">View report <b>→</b></a>
        </header>
        <div class="country-grid">
            @foreach ([
                ['United States','100K','50%','violet'],
                ['Europe','1M','80%','green'],
                ['Australia','450K','40%','cyan'],
                ['India','1B','90%','slate'],
            ] as $country)
            <div class="country">
                <div class="country-name"><i class="{{ $country[3] }}"></i>{{ $country[0] }}</div>
                <strong>{{ $country[1] }} <small>{{ $country[2] }}</small></strong>
                <div class="country-bar"><span class="{{ $country[3] }}" style="width:{{ $country[2] }}"></span></div>
            </div>
            @endforeach
        </div>
        <div class="rings">
            @foreach ([['75','New users','first-time visitors','red'],['50','New purchases','from new visits','cyan'],['90','Bounce rate','avg engagement','orange']] as $ring)
            <div class="ring-item">
                <div class="progress-ring {{ $ring[3] }}" style="--value:{{ $ring[0] }}">{{ $ring[0] }}%</div>
                <div><strong>{{ $ring[1] }}</strong><span>{{ $ring[2] }}</span></div>
            </div>
            @endforeach
        </div>
    </section>

    <div class="dashboard-grid">
        <section class="dash-card monthly">
            <header class="card-heading">
                <div><span class="eyebrow">PERFORMANCE</span><h2>Monthly stats</h2></div>
                <a href="#">April 2026</a>
            </header>
            <div class="chart">
                <div class="y-labels"><span>200</span><span>100</span><span>0</span></div>
                <svg viewBox="0 0 700 230" preserveAspectRatio="none" aria-label="Monthly stats chart">
                    <defs><linearGradient id="chartFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#2f6cf3" stop-opacity=".23"/><stop offset="1" stop-color="#2f6cf3" stop-opacity=".08"/></linearGradient></defs>
                    <path class="grid-line" d="M0 1H700M0 115H700M0 229H700"/>
                    <path class="area" d="M0 180 C45 190 55 187 80 173 S125 158 155 170 S195 160 220 140 S260 132 290 145 S325 139 350 121 S395 111 425 124 S465 115 490 91 S535 74 565 94 S610 86 630 62 S670 37 700 25 L700 230 L0 230Z"/>
                    <path class="line" d="M0 180 C45 190 55 187 80 173 S125 158 155 170 S195 160 220 140 S260 132 290 145 S325 139 350 121 S395 111 425 124 S465 115 490 91 S535 74 565 94 S610 86 630 62 S670 37 700 25"/>
                </svg>
                <div class="months">@foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $month)<span>{{ $month }}</span>@endforeach</div>
            </div>
            <div class="chart-summary">
                @foreach ([['Sales growth','54%'],['Dec sales','$185K'],['Profit growth','60%'],['Dec profit','$72K']] as $stat)
                <div><span>{{ $stat[0] }}</span><strong>{{ $stat[1] }} <small>↗</small></strong></div>
                @endforeach
            </div>
        </section>

        <section class="dash-card todo">
            <header class="card-heading">
                <div><span class="eyebrow">PERSONAL</span><h2>Todo list</h2></div>
                <a href="#">Add task&nbsp; +</a>
            </header>
            <div class="todo-list">
                @foreach ([
                    ['Call John for dinner','ANYTIME','neutral',false],
                    ['Book boss flight','2 DAYS','info',false],
                    ['Hit the gym','3 MIN','danger',false],
                    ['Give purchase report','LOW PRIORITY','warning',false],
                    ['Watch Foundation S03E04','TOMORROW','info',false],
                    ['Give purchase report','DONE','success',true],
                ] as $task)
                <div class="todo-row {{ $task[3] ? 'completed' : '' }}"><span class="check">{{ $task[3] ? '✓' : '' }}</span><span class="task-name">{{ $task[0] }}</span><span class="tag {{ $task[2] }}">{{ $task[1] }}</span></div>
                @endforeach
            </div>
        </section>

        <section class="dash-card sales">
            <header class="card-heading">
                <div><span class="eyebrow">COMMERCE</span><h2>Sales report</h2></div>
            </header>
            <div class="sales-period"><div><span class="eyebrow">PERIOD</span><strong>April 2026</strong></div><div class="sales-total"><small>$</small>6,000</div></div>
            <div class="sales-table">
                <div class="sales-row sales-th"><span>NAME</span><span>STATUS</span><span>DATE</span><span>PRICE</span></div>
                @foreach ([
                    ['Item #1','Unavailable','danger','Apr 18','$12','positive'],
                    ['Item #2','New','violet','Apr 19','$34','positive'],
                    ['Item #3','New','violet','Apr 20','−$45','negative'],
                    ['Item #4','Unavailable','danger','Apr 21','$65','positive'],
                    ['Item #5','Used','orange','Apr 22','$78','positive'],
                    ['Item #6','Used','orange','Apr 23','−$88','negative'],
                    ['Item #7','Old','yellow','Apr 22','$56','positive'],
                ] as $sale)
                <div class="sales-row"><strong>{{ $sale[0] }}</strong><span><i class="tag {{ $sale[2] }}">{{ $sale[1] }}</i></span><span>{{ $sale[3] }}</span><b class="{{ $sale[5] }}">{{ $sale[4] }}</b></div>
                @endforeach
            </div>
            <a class="sales-link" href="#">Check all sales&nbsp; →</a>
        </section>

        <section class="dash-card weather">
            <header class="card-heading">
                <div><span class="eyebrow">TODAY</span><h2>Weather</h2></div>
                <a href="#">Riga, LV</a>
            </header>
            <div class="weather-current">
                <div class="weather-main"><span class="weather-icon">☀<i>☁</i></span><div><strong>32<small>°F</small></strong><span>Partly cloudy · light breeze</span></div></div>
                <div class="weather-date"><strong>Thursday</strong><span>APR 23, 2026</span></div>
            </div>
            <div class="weather-details">
                <div><span>Wind</span><strong>10<small> km/h</small></strong></div>
                <div><span>Sunrise</span><strong>05:32<small> am</small></strong></div>
                <div><span>Pressure</span><strong>1013<small> hPa</small></strong></div>
            </div>
            <div class="forecast">
                @foreach ([['THU','☁','32°'],['FRI','☼','30°'],['SAT','☂','28°'],['SUN','☁','32°'],['MON','☔','24°'],['TUE','≋','28°'],['WED','☁','32°']] as $i => $day)
                <div class="{{ $i === 0 ? 'active' : '' }}"><span>{{ $day[0] }}</span><b>{{ $day[1] }}</b><strong>{{ $day[2] }}</strong></div>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection
