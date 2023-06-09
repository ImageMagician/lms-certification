@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-4">
                    <div class="card-header">{{ __('Installation Information') }}</div>
                    <div class="card-body">
                        <h1 class="h3">Installation Information</h1>
                        @if ( $activity->install_address == null)
                        <p>Provide us with the location and information for your first installation. A Lion Energy representative will schedule a date and time to be on site to review and certify the installation.</p>
                        @else
                        <p>Revise the date and location information of your first installation project.</p>
                        @endif
                        <form method="post" action="{{ route('installation-process') }}">
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end" for="install_address">{{ __("Address") }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="install_address" name="install_address" value="{{$activity->install_address}}" placeholder="Street address">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end" for="install_city">{{ __("City, State Zip") }}</label>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" id="install_city" name="install_city" value="{{$activity->install_city}}" placeholder="City name">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" id="install_state" name="install_state" value="{{$activity->install_state}}" placeholder="State">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="install_zip" name="install_zip" value="{{$activity->install_zip}}" placeholder="Zip code">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end" for="install_batteries">{{ __("Battery quantity") }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="install_batteries" name="install_batteries" value="{{$activity->install_batteries}}" placeholder="Number of batteries being installed.">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end" for="install_inverters">{{ __("Inverter quantity") }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="install_inverters" name="install_inverters" value="{{$activity->install_inverters}}" placeholder="Number of inverters being installed.">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 offset-md-4">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="{{ $module->id }}">
                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
