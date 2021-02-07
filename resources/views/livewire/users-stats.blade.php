<div class="text-center w-100 mb-3" style="display:block;">

    <div class="input-group mb-3">
        <label class="input-group-text" for="period">
            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                <path fill="currentColor"
                      d="M19,19H5V8H19M16,1V3H8V1H6V3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3H18V1M17,12H12V17H17V12Z"/>
            </svg>
        </label>
        <select class="form-select" id="subscription" wire:model="period">
            <option value="all" selected>All Time</option>
            <option value="today">Today</option>
            <option value="week">Week</option>
            <option value="month">Month</option>
        </select>
    </div>

    <div class="col-md-2 m-1" style="display:inline-block;">
        <div class="shadow p-3 rounded text-center bg-light">
            <svg style="width:48px;height:48px" viewBox="0 0 24 24" class="m-auto">
                <path fill="currentColor"
                      d="M16 17V19H2V17S2 13 9 13 16 17 16 17M12.5 7.5A3.5 3.5 0 1 0 9 11A3.5 3.5 0 0 0 12.5 7.5M15.94 13A5.32 5.32 0 0 1 18 17V19H22V17S22 13.37 15.94 13M15 4A3.39 3.39 0 0 0 13.07 4.59A5 5 0 0 1 13.07 10.41A3.39 3.39 0 0 0 15 11A3.5 3.5 0 0 0 15 4Z"/>
            </svg>
            <h2>{{ $stats['total'] }}</h2>
            <h4>Total</h4>
        </div>
    </div>
    <div class="col-md-2 m-1" style="display:inline-block;">
        <div class="shadow p-3 rounded text-center bg-light">
            <svg style="width:48px;height:48px" viewBox="0 0 24 24" class="m-auto">
                <path fill="currentColor"
                      d="M7,15H9C9,16.08 10.37,17 12,17C13.63,17 15,16.08 15,15C15,13.9 13.96,13.5 11.76,12.97C9.64,12.44 7,11.78 7,9C7,7.21 8.47,5.69 10.5,5.18V3H13.5V5.18C15.53,5.69 17,7.21 17,9H15C15,7.92 13.63,7 12,7C10.37,7 9,7.92 9,9C9,10.1 10.04,10.5 12.24,11.03C14.36,11.56 17,12.22 17,15C17,16.79 15.53,18.31 13.5,18.82V21H10.5V18.82C8.47,18.31 7,16.79 7,15Z"/>
            </svg>
            <h2>{{ $stats['subscriptions'] }}</h2>
            <h4>Subscriptions</h4>
        </div>
    </div>

    {{--    <div class="col-md-2 m-1" style="display:inline-block;">
            <div class="shadow p-3 rounded text-center bg-light">
                <svg style="width:48px;height:48px" viewBox="0 0 24 24" class="m-auto">
                    <path fill="currentColor"
                          d="M10.63,14.1C12.23,10.58 16.38,9.03 19.9,10.63C23.42,12.23 24.97,16.38 23.37,19.9C22.24,22.4 19.75,24 17,24C14.3,24 11.83,22.44 10.67,20H1V18C1.06,16.86 1.84,15.93 3.34,15.18C4.84,14.43 6.72,14.04 9,14C9.57,14 10.11,14.05 10.63,14.1V14.1M9,4C10.12,4.03 11.06,4.42 11.81,5.17C12.56,5.92 12.93,6.86 12.93,8C12.93,9.14 12.56,10.08 11.81,10.83C11.06,11.58 10.12,11.95 9,11.95C7.88,11.95 6.94,11.58 6.19,10.83C5.44,10.08 5.07,9.14 5.07,8C5.07,6.86 5.44,5.92 6.19,5.17C6.94,4.42 7.88,4.03 9,4M17,22A5,5 0 0,0 22,17A5,5 0 0,0 17,12A5,5 0 0,0 12,17A5,5 0 0,0 17,22M16,14H17.5V16.82L19.94,18.23L19.19,19.53L16,17.69V14Z"/>
                </svg>
                <h2>{{ $today['users'] }}</h2>
                <h4>Users Today</h4>
            </div>
        </div>
        <div class="col-md-2 m-1" style="display:inline-block;">
            <div class="shadow p-3 rounded text-center bg-light">
                <svg style="width:48px;height:48px" viewBox="0 0 24 24" class="m-auto">
                    <path fill="currentColor"
                          d="M7,10H12V15H7M19,19H5V8H19M19,3H18V1H16V3H8V1H6V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z"/>
                </svg>
                <h2>{{ $today['subscriptions'] }}</h2>
                <h4>Subs Today</h4>
            </div>
        </div>--}}

    {{--  <br>

      <div class="col-md-2 m-1" style="display:inline-block;">
          <div class="shadow p-3 rounded text-center bg-light">
              <h2>{{ $subscriptions['free'] }}</h2>
              <h4>Free Plan</h4>
          </div>
      </div>
      <div class="col-md-2 m-1" style="display:inline-block;">
          <div class="shadow p-3 rounded text-center bg-light">
              <h2>{{ $subscriptions['standard'] }}</h2>
              <h4>Standard Plan</h4>
          </div>
      </div>
      <div class="col-md-2 m-1" style="display:inline-block;">
          <div class="shadow p-3 rounded text-center bg-light">
              <h2>{{ $subscriptions['premium'] }}</h2>
              <h4>Premium Plan</h4>
          </div>
      </div>--}}
</div>
