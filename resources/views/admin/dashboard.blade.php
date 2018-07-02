@extends('layout')

@section('title')
DASHBOARD
@endsection

@section('css')
{{ asset('imports/css/dashboard.css') }}
@endsection

@section('content')
<div class="container-fluid">
   <br>
   <div class="row">
      <div class="col m-1" id="new-customers" onclick="window.location='{{ url("accounts/members") }}'">
      <div class="row">
        <div class ="col-xs-1">         
           <i class="material-icons group">group</i>
        </div>
        <div class ="col-xs-2">
           <p class="title">New Customers  <span class="last-time"></br>Last 7 days</span></p>
        </div>
        <div class ="col-xs-9 mx-auto">
           <h1 class="newmember-count">{{$newmembers}}</h1>
        </div>
       </div>
    </div>
      <div class="col m-1" id="new-reload" onclick="window.location='{{ url("logs/reload") }}'">
        <div class="row">
          <div class ="col-xs-1"> 
            <i class="material-icons reload">credit_card</i>
          </div>
         <div class ="col-xs-2">
            <p class="title">Reload Sales <span class="last-time"></br>Last 30 days</span></p>
         </div>
         <div class ="col-xs-9 mx-auto">
            <h1 class="reload-sales">
            @if($reloadsales != '')
              {{ number_format($reloadsales,2) }}
            @else 
              {{ '0.00' }} 
            @endif
          </h1>
         </div>
        </div>
      </div>
      <div class="col m-1" id="new-sales" onclick="window.location='{{ url("logs/sales") }}'">
        <div class="row">
          <div class ="col-xs-1"> 
            <i class="material-icons add_shopping_cart">add_shopping_cart</i>
          </div>
         <div class ="col-xs-2">
            <p class="title">Product Sales <span class="last-time"></br>Last 30 days</span></p>
         </div>
         <div class ="col-xs-9 mx-auto">
            <h1 class="product-sales">
              @if($sales != '')
              {{ number_format($sales,2) }}
            @else 
              {{ '0.00' }} 
            @endif
            </h1>
         </div>
       </div> 
      </div>
   </div>

   <div class="row">
      <div class="col m-1 border" id="member-walkin">
        <!--  <h6>Member vs Walk-in</h6> -->
         <canvas id="salestoday">
         </canvas>  
      </div>
      <div class="col m-1 border" id="sales-day">
         <!-- <h6>Sales for {{ \Carbon\Carbon::now()->format('Y')}}</h6> -->
         <canvas id="sales-for-year">
         </canvas>
      </div>
   </div>

    <div class="row">
      <div class="col m-1 border" id="top-products">
        <center><h6>Top Selling Products</h6></center>
        <br>
        <div class="row">
                <div class="col">
                  <h5 class="label-product">Product</h5>
                </div>
                <div class="col">
                  <h5 class="label-total">Total</h5>
                </div>
              </div>
      </div> 

      <div class="col m-1 border" id="sales-payment">
        <canvas id="payment-mode">
        </canvas>
      </div>
      <div class="col m-1 border" id="active-members">
         <center><h6>Most Active Members</h6></center>
        <br>
        <div class="row">
                <div class="col">
                  <h5 class="label-product">Member</h5>
                </div>
                <div class="col">
                  <h5 class="label-total">Total Purchase</h5>
                </div>
              </div>
          @foreach($topmembers as $member)
            @if($loop->first)
              <div class="row">
                <div class="col">
                  <h4>{{$loop->iteration . ". " . $member->name}}</h4>
                </div>
                <div class="col">
                  <h2 class="top-product-price">₱ {{number_format($member->amount_due,2)}}</h2>
                </div>
              </div>
            @else
              <div class="row">
                <div class="col">
                  <h6 class="label-product2">{{$loop->iteration . ". " . $member->name}}</h6>
                </div>
                <div class="col">
                  <h6 class="label-total2">₱ {{number_format($member->amount_due,2)}}</h6>
                </div>
              </div>
            @endif
          @endforeach
      </div>
    </div>

</div>

<script type="text/javascript">
   var bar_ctx = document.getElementById('payment-mode').getContext('2d');
   var cashgradient = bar_ctx.createLinearGradient(0, 0, 0, 600);
   cashgradient.addColorStop(0, '#7ef6b8');
   cashgradient.addColorStop(1, '#66ca72');

   var cardgradient = bar_ctx.createLinearGradient(0, 0, 0, 600);
   cardgradient.addColorStop(0, '#f4b591');
   cardgradient.addColorStop(1, '#eb5757');

   var cash = <?php echo $cashpay; ?>;
   var load = <?php echo $loadpay; ?>;
   new Chart(document.getElementById("payment-mode"), {
    type: 'doughnut',
    data: {
      labels: ["Cash", "Card Load"],
      datasets: [
        {
          backgroundColor: [cashgradient, cardgradient],
          hoverBackgroundColor: [cashgradient, cardgradient],
          hoverBorderWidth: 2,
          hoverBorderColor: [cashgradient, cardgradient],
          data: [cash,load]
        }
      ]
    },
    options: {
      title: {
         display: true,
         text: 'Sales by Payment Mode',
         fontSize: '16',
         fontColor: 'black',
         fontStyle: 'normal',
         fontFamily: 'Montserrat',
      },
      cutoutPercentage: 70,
      responsive:true,
      maintainAspectRatio: false,
    }
   });

   var year = '<?php echo \Carbon\Carbon::now()->format('F d, Y') ?>'
   var areagradient = bar_ctx.createLinearGradient(0, 0, 0, 450);
   areagradient.addColorStop(0, '#3BD0C0');
  areagradient.addColorStop(1, '#FFFFFF');
    new Chart(document.getElementById("salestoday"), {
    type: 'line',
    data: {
         labels: ["10-11", "11-12", "12-1", "1-2", "2-3", "3-4", "4-5", "5-6", "6-7", "7-8",],
         datasets: [{
            data: <?php echo json_encode($salestoday)?>,
            label: "",
            borderWidth: 2,
            borderColor: "#3e95cd",
            fill: 'start',
            backgroundColor:  areagradient,
         }]
      },
      options: {
         title: {
            display: true,
            text: 'Product Sales for Today - ' + year,
            fontSize: '17',
            fontColor: 'black',
            fontStyle: 'normal',
            fontFamily: 'Montserrat',
         },
         plugins: {
            filler: {
                propagate: true
            }
         },
         elements: {
           line: {
               tension: 0
           }
         },
         scales: {
            yAxes: [{
                  display: true,
                  ticks: {
                      beginAtZero: true,
                      steps: 1000,
                      stepValue: 5,
                  }
              }],
            xAxes: [{
               ticks: {
                  autoSkip: false
               }
            }]
         },
         responsive:true,
         maintainAspectRatio: false,
         legend: {
            display: false
         },
      },

   });


   var year = <?php echo \Carbon\Carbon::now()->format('Y') ?>;
   var areagradient = bar_ctx.createLinearGradient(0, 0, 0, 450);
   areagradient.addColorStop(0, '#3BD0C0');
  areagradient.addColorStop(1, '#FFFFFF');
    new Chart(document.getElementById("sales-for-year"), {
    type: 'line',
    data: {
         labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
         datasets: [{
            data: <?php echo json_encode($yearsales)?>,
            label: "",
            borderWidth: 2,
            borderColor: "#3e95cd",
            fill: 'start',
            backgroundColor:  areagradient,
         }]
      },
      options: {
         title: {
            display: true,
            text: 'Product Sales for ' + year,
            fontSize: '17',
            fontColor: 'black',
            fontStyle: 'normal',
            fontFamily: 'Montserrat',
         },
         plugins: {
            filler: {
                propagate: true
            }
         },
         elements: {
           line: {
               tension: 0
           }
         },
         scales: {
            yAxes: [{
                  display: true,
                  ticks: {
                      beginAtZero: true,
                      steps: 1000,
                      stepValue: 5,
                  }
              }],
            xAxes: [{
               ticks: {
                  autoSkip: false
               }
            }]
         },
         responsive:true,
         maintainAspectRatio: false,
         legend: {
            display: false
         },
      },

   });

   
</script>
@endsection