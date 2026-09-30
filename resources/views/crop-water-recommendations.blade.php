@extends('layouts.app', ['title' => 'Crop recommendations | Lima na Mae'])

@section('content')
    <div class="dashboard-intro">
        <div>
            <p class="eyebrow">Field toolkit</p>
            <h1>Grow with the water you have.</h1>
            <p class="dashboard-lede">Tell us where you farm, how the rain behaves, and what resources you can bring. We will suggest hardy crops and practical ways to hold water.</p>
        </div>
    </div>

    <div class="planner-layout recommendation-layout">
        <section class="dashboard-section planner-form-section">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Start with your context</p>
                    <h2>Find a fit</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('crop-water-recommendations.store') }}" class="planner-form">
                @csrf
                <label class="field-label" for="location">Where is your farm?</label>
                <select class="field-input" id="location" name="location" required>
                    <option value="">Choose a county</option>
                    @foreach ($locations as $key => $location)
                        <option value="{{ $key }}" @selected(old('location', $selected['location'] ?? '') === $key)>{{ $location['label'] }}</option>
                    @endforeach
                </select>

                <label class="field-label" for="rainfall">How does rainfall behave?</label>
                <select class="field-input" id="rainfall" name="rainfall" required>
                    <option value="">Choose a rainfall pattern</option>
                    @foreach ($rainfallPatterns as $key => $pattern)
                        <option value="{{ $key }}" @selected(old('rainfall', $selected['rainfall'] ?? '') === $key)>{{ $pattern['label'] }}</option>
                    @endforeach
                </select>

                <label class="field-label" for="resources">What resources can you use?</label>
                <select class="field-input" id="resources" name="resources" required>
                    <option value="">Choose your resource level</option>
                    @foreach ($resourceLevels as $key => $resource)
                        <option value="{{ $key }}" @selected(old('resources', $selected['resources'] ?? '') === $key)>{{ $resource['label'] }}</option>
                    @endforeach
                </select>
                <p class="field-hint">Think about money, available labour, tools, and dependable water together.</p>

                <button class="primary-button" type="submit">Recommend my options <span aria-hidden="true">-&gt;</span></button>
            </form>
        </section>

        @isset($result)
            <section class="dashboard-section planner-result-section recommendation-result">
                <p class="eyebrow">Your field recommendation</p>
                <h2>{{ $result['location'] }} · {{ $result['rainfall'] }}</h2>
                <p class="planner-explanation">{{ $result['location_note'] }} {{ $result['summary'] }}</p>

                <div class="recommendation-columns">
                    <div>
                        <h3>Drought-tolerant crops</h3>
                        <div class="recommendation-list">
                            @foreach ($result['crops'] as $crop)
                                <article>
                                    <div class="recommendation-heading"><strong>{{ $crop['name'] }}</strong><span>{{ $crop['priority'] }}</span></div>
                                    <p>{{ $crop['reason'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3>Water-harvesting methods</h3>
                        <div class="recommendation-list">
                            @foreach ($result['harvesting_methods'] as $method)
                                <article>
                                    <div class="recommendation-heading"><strong>{{ $method['name'] }}</strong><span>{{ $method['effort'] }}</span></div>
                                    <p>{{ $method['reason'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>

                <p class="planner-status">Treat this as a starting point. Confirm soil conditions, available water, local construction guidance, and crop advice before investing.</p>
            </section>
        @else
            <section class="dashboard-section next-section planner-empty">
                <p class="eyebrow">A practical starting point</p>
                <h2>Make each rainy season work harder.</h2>
                <p>Good recommendations balance the crop, the rain, and the work needed to capture water. Start with one manageable change and learn from the next season.</p>
            </section>
        @endisset
    </div>
@endsection