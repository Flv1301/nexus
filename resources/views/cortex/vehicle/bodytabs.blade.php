<div class="card-body">
    <div class="tab-content">
        <div class="tab-pane active" id="tab_data" role="tabpanel">
            @include('cortex.vehicle.vehicledata')
        </div>
        <div class="tab-pane" id="tab_positions" role="tabpanel">
            @include('cortex.vehicle.vehicle_moviments')
        </div>
{{--        <div class="tab-pane" id="tab_alert" role="tabpanel">--}}
{{--            @include('cortex.vehicle.vehiclealert')--}}
{{--        </div>--}}
    </div>
</div>
