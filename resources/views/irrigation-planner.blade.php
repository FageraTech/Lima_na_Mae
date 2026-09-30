@extends('layouts.app', ['title' => 'Irrigation planner | Lima na Mae'])

@section('content')
    <div class="dashboard-intro">
        <div>
            <p class="eyebrow">Field toolkit</p>
            <h1>Water the right amount.</h1>
            <p class="dashboard-lede">Give us four simple details. We will turn crop water demand into a weekly plan and a practical tank size.</p>
        </div>
    </div>

    <div class="planner-layout">
        <section class="dashboard-section planner-form-section">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Start with your field</p>
                    <h2>Build a plan</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('irrigation-planner.store') }}" class="planner-form">
                @csrf
                <label class="field-label" for="crop">What are you growing?</label>
                <select class="field-input" id="crop" name="crop" required>
                    <option value="">Choose a crop</option>
                    @foreach ($crops as $key => $crop)
                        <option value="{{ $key }}" @selected(old('crop', $selected['crop'] ?? '') === $key)>{{ $crop['label'] }}</option>
                    @endforeach
                </select>

                <label class="field-label" for="acreage">How many acres?</label>
                <input class="field-input" id="acreage" name="acreage" type="number" min="0.01" max="10000" step="0.01" value="{{ old('acreage', $selected['acreage'] ?? '') }}" placeholder="For example, 2.5" required>

                <label class="field-label" for="soil">What is your soil like?</label>
                <select class="field-input" id="soil" name="soil" required>
                    <option value="">Choose soil type</option>
                    @foreach ($soils as $key => $soil)
                        <option value="{{ $key }}" @selected(old('soil', $selected['soil'] ?? '') === $key)>{{ $soil['label'] }}</option>
                    @endforeach
                </select>
                <p class="field-hint">Sandy soil dries faster, so the plan splits water into more visits.</p>

                <label class="field-label" for="water_source_id">Which water source will you use?</label>
                <select class="field-input" id="water_source_id" name="water_source_id" required>
                    <option value="">Choose a water source</option>
                    @foreach ($waterSources as $waterSource)
                        <option value="{{ $waterSource->id }}" @selected((string) old('water_source_id', $selected['water_source_id'] ?? '') === (string) $waterSource->id)>
                            {{ $waterSource->name }}{{ $waterSource->capacity ? ' · '.number_format($waterSource->capacity).' '.$waterSource->capacity_unit : '' }}
                        </option>
                    @endforeach
                </select>

                <button class="primary-button" type="submit">Calculate my plan <span aria-hidden="true">-&gt;</span></button>
            </form>
        </section>

        @isset($result)
            <section class="dashboard-section planner-result-section">
                <p class="eyebrow">Your plain-language plan</p>
                <h2>{{ number_format($result['weekly_litres']) }} litres each week</h2>
                <p class="planner-explanation">{{ $result['explanation'] }}</p>

                <div class="planner-metrics">
                    <div><span>Daily demand</span><strong>{{ number_format($result['daily_litres']) }} L</strong></div>
                    <div><span>Tank recommendation</span><strong>{{ number_format($result['tank_litres']) }} L</strong></div>
                    <div><span>Water source</span><strong>{{ $result['water_source'] }}</strong></div>
                </div>

                <h3>Weekly watering rhythm</h3>
                <div class="schedule-list">
                    @foreach ($result['schedule'] as $watering)
                        <div><strong>{{ $watering['day'] }}</strong><span>{{ number_format($watering['litres']) }} litres</span></div>
                    @endforeach
                </div>

                <div class="planner-note">
                    <strong>How the estimate works</strong>
                    <p>We use FAO-style crop demand: reference ET of {{ $result['assumptions']['reference_et'] }} mm/day × a {{ $result['assumptions']['crop_coefficient'] }} crop coefficient, then allow for {{ round((1 - $result['assumptions']['irrigation_efficiency']) * 100) }}% field losses. Actual demand changes with rain, wind, heat, and crop growth stage.</p>
                </div>

                @if ($result['capacity_status'] === 'enough')
                    <p class="planner-status planner-status-good">This source's listed daily capacity covers the estimated daily demand.</p>
                @elseif ($result['capacity_status'] === 'short')
                    <p class="planner-status planner-status-warning">This source may not cover the estimated daily demand. Consider a larger source, a storage tank, or a smaller planted area.</p>
                @else
                    <p class="planner-status">This source has no capacity figure yet, so compare the daily estimate with what it reliably supplies.</p>
                @endif
            </section>
        @else
            <section class="dashboard-section next-section planner-empty">
                <p class="eyebrow">A useful starting point</p>
                <h2>Know before you pump.</h2>
                <p>Use the estimate to plan pump time, spot a storage gap, and avoid giving the whole field one large drink when the soil needs smaller visits.</p>
            </section>
        @endisset
    </div>
@endsection