@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 py-5">
                <h1>Set Regional Rep Areas</h1>
                <div id="container" class="svg-container">
                    <div class="position-relative mt-3">
                        <div class="p-2 border">
                            <div class="row">
                                @php
                                    $rep_list = $rep->toArray();
                                @endphp
                                @foreach($rep as $rep)
                                <div class="col-6 col-md-4 col-lg-3 col-xl-2" style="white-space:nowrap">
                                    <div class="legend-tile selected_{{$rep->rsm}}"></div>
                                    <div class="legend-text">{{ $rep->first_name }} {{ $rep->last_name }}</div>
                                </div>
                                @endforeach
                                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                                    <div class="legend-tile"></div>
                                    <div class="legend-text">Undesignated</div>
                                </div>
                            </div>
                        </div>
                        @if ( $admin->super_admin )
                        <div class="text-center">
                            <div id="form-container" class="border border-1 p-1">
                                <form id="states_form" action="{{route('rsm-map-submit')}}" method="post">
                                    <label for="region" class="form-label d-inline-block me-1">Designate Selected Region:</label>
                                    <select name="region" id="region" class="form-control d-inline-block w-auto" style="padding-right:30px;" disabled="disabled">
                                        <option value="">Please select...</option>
                                        @foreach($rep_list as $rep)
                                            <option value="{{ $rep->rsm }}">{{ $rep->first_name }} {{ $rep->last_name }}</option>
                                        @endforeach
                                        <option value="0">Undesignate area</option>
                                    </select>
                                    @csrf
                                    <input id="states_data" type="hidden" name="states">
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                    <svg id="map" class="map svg-content-responsive" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMinYMin meet" viewBox="0 0 960  550">
                        <g>
                            <g id="states">
                                @foreach($state as $state)
                                <a id="{{$state->abbrev}}" rel="nofollow" title="{{ $state->name }}"
                                        @if ($state->rep !== null)
                                            class="selected_{{$state->rep}}"
                                        @endif
                                >
                                    <path d="{{ $state->map_path }}" stroke="#FFFFFF" stroke-dasharray="0, 0" stroke-width="1"></path>
                                    <text x="{{ $state->map_text_x }}" y="{{ $state->map_text_y }}" title="{{ $state->name }}">{{ $state->abbrev }}</text>
                                </a>
                                @endforeach
                            </g>
                        </g>
                    </svg>
                </div>

                <div id="response"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style id="style">
        #states a {
            text-decoration: none;
        }
        #states a path {
            transition: fill .5s ease;
            -moz-transition: fill .5s ease;
            -webkit-transition: fill .5s ease;
        }

        #states a path,
        .legend-tile {
            fill: #999999;
            background-color: #999999;
        }

        #states a:hover path {
            transition: fill .5s ease;
            -moz-transition: fill .5s ease;
            -webkit-transition: fill .5s ease;
            cursor: pointer;
            fill: #777;
        }

        #states a.selected_1 path,
        .legend-tile.selected_1 {
            fill: #993333;
            background-color:#933;
        }

        #states a.selected_1:hover path {
            fill: #791c1c;
        }

        #states a.selected_2 path,
        .legend-tile.selected_2 {
            fill: #775588;
            background-color: #775588;
        }

        #states a.selected_2:hover path {
            fill: #5d3b6e;
        }

        #states a.selected_3 path,
        .legend-tile.selected_3 {
            fill:#6688aa;
            background-color:#6688aa;
        }

        #states a.selected_3:hover path {
            fill:#4c7ea0;
        }

        #states a.selected_4 path,
        .legend-tile.selected_4 {
            fill:#cccc33;
            background-color:#cccc33;
        }

        #states a.selected_4:hover path {
            fill:#b3b31c;
        }

        #states a.selected_5 path,
        .legend-tile.selected_5 {
            fill:#66aa66;
            background-color:#66aa66;
        }

        #states a.selected_5:hover path {
            fill:#4c804c;
        }

        #states a.selected_6 path,
        .legend-tile.selected_6 {
            fill:#c69;
            background-color:#c69;
        }

        #states a.selected_6:hover path {
            fill:#b35c80;
        }



        #states a.clicked text {
            fill:#900;
            font-weight:bold;
        }

        #states a.clicked path {
            fill:#ffcf51 !important;
        }

        #states a.clicked:hover path {
            fill: rgb(245,202,76) !important;
        }

        #states a text {
            cursor: pointer;
            fill: #222222;
        }

        #states a:hover text {
            fill: #000;
            color: #000;
        }

        #states a.inverse text:hover {
            fill: #EAEAEA;
        }

        #states a.inverse text {
            fill: #222222;
        }

        .CCMcredit a {
            color: #81AC8B;
        }

        #states a text {
            font-size: 9px;
        }

        #form-container {
            position:absolute;
            white-space:nowrap;
            left:50%;
            top:100%;
            margin-top:0.5rem;
        }

        .legend-tile    {
            display: inline-block;
            height:.75rem;
            width:.75rem;
            line-height:0;
            font-size:0;
            border:rgba(0,0,0,.1);
            vertical-align:middle;
            margin-right:.5rem;
        }

        .legend-text {
            display:inline-block;
            font-size:.75rem;
            line-height:14px;
        }

        @media (max-width:993px) {
            #form-container {
                position:static;
                display:inline-block;
                width:auto;
                left:auto;
                top:auto;
                margin-left:auto;
                margin-right:auto;
            }
        }
    </style>

    <script>
        setTimeout( () => {
            const states = document.querySelectorAll('a');
            const select = document.getElementById('region');
            let i;

            if ( select != null ) {
                for (i = 0; i < states.length; i++) {
                    states[i].addEventListener('click', statesToggle);
                }
                select.addEventListener('change', assignToggledStates);
            }
        }, 500);

        function statesToggle() {
            this.classList.toggle('clicked');

            const selected_states = document.querySelectorAll('.clicked');
            if ( selected_states.length == 0) {
                document.getElementById('region').setAttribute('disabled', 'disabled');
            }
            else {
                document.getElementById('region').removeAttribute('disabled');
            }
        }

        function assignToggledStates() {
            let a;

            // get all states that have been selected
            const selected_states = document.querySelectorAll('a.clicked');

            // create a class for 'selected' + option value
            const option = 'selected_' + this.value;
            const states_array  = [];

            const class_array = [
                'selected_1',
                'selected_2',
                'selected_3',
                'selected_4',
                'selected_5',
                'selected_6',
            ];

            // Assign 'selected_' class to all selected states
            for(a=0; a<selected_states.length; a++) {
                // clear off any previously selected class
                for( let b = 0; b < class_array.length; b++ ) {
                    selected_states[a].classList.remove(class_array[b]);
                }

                selected_states[a].classList.remove('clicked');
                selected_states[a].classList.add(option);
            }

            // get all selected states. This is done separately from the above for loop
            // because there may already be some states with the 'selected_' class that
            // are not part of the current attribution
            const all_selected_states = document.querySelectorAll('.' + option);
            for (a=0; a<all_selected_states.length; a++) {
                states_array.push(all_selected_states[a].id);
            }

            // Assign values array to the states_data hidden field
            document.getElementById('states_data').value = states_array;
            document.getElementById('states_form').submit();
        }
    </script>
@endsection
