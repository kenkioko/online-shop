@extends('layouts.dash')

@section('page_header', 'Processing Order')

@section('sidebar')
  @dash_sidebar(['page' => 'orders'])
    <!-- print sidebar -->
  @enddash_sidebar
@endsection

@section('breadcrumb')
  @breadcrum(['extra_class' => 'float-sm-right'])
    <li class="breadcrumb-item">
      <a href="{{ route('admin.dash') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('admin.orders.index') }}">Orders</a>
    </li>
    <li class="breadcrumb-item active">{{ $order->order_no }}</li>
  @endbreadcrum()
@endsection

@php
  use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
  <div class="container-fluid">

    @include('shared.show_alert')
     

    <div class="card">
      @if($order->status != App\Models\Order::getStatus('completed'))
        <div class="card-header">
        <div class="card-tools">
          @can('orders.update')
            <button type="button"
              class="btn btn-sm btn-outline-success pop"
              data-container="body" data-toggle="popover" data-placement="bottom"
              data-content="Save"
              onclick="on_save()"
            ><i class="nav-icon fas fa-save"></i></button><!-- /.button -->
          @endcan

          @can('orders.delete')
            <button type="button"
              class="btn btn-sm btn-outline-danger pop"
              data-container="body" data-toggle="popover" data-placement="bottom"
              data-content="Reject Order"
              onclick="on_reject()"
            ><i class="nav-icon fas fa-trash-alt"></i></button><!-- /.button -->
          @endcan
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
      @endif

      <div class="card-body">

        <div class="d-flex flex-row">
          <!-- Order Details -->
          <table id="order_details">
            <tr>
              <td><strong>Order No: </strong></td>
              <td class="px-2">{{ $order->order_no }}</td>
            </tr>
            <tr>
              <td><strong>Order Date: </strong></td>
              <td class="px-2">{{ $order->order_date->toDayDateTimeString() }}</td>
            </tr>
            <tr>
              <td><strong>Order Status: </strong></td>
              <td class="px-2">{{ App\Models\Order::getStatus($order->status) }}</td>
            </tr>
            <tr>
              <td><strong>Order Amount: </strong></td>
              <td class="px-2"><span id="order_total"></span></td>
            </tr>
          </table>


          <table id="Customer_details" class="ml-auto">
            <!-- Customer Details -->
            <tr>
              <td><strong>Customer Name: </strong></td>
              <td class="px-2">{{ $order->user()->first()->name }}</td>
            </tr>

            <!-- Seller Details -->
            <tr>
              <td><strong>Seller Name: </strong></td>
              <td class="px-2">{{ $shop->name }}</td>
            </tr>
          </table>
        </div><!-- /.d-flex -->

        <div class="border-top border-bottom mt-3">
          <h5>Order Details:</h5>
          @data_table(['table_id' => 'table_list'])
            @slot('head')
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Progress Status</th>
              </tr>
            @endslot

            @php
              $order_total = 0;
            @endphp

            <script type="text/javascript">
              var items_status = {};
            </script>

            @foreach($order_items as $item)
              @php
                $order_total += $item->amount;
              @endphp

              <script type="text/javascript">
                items_status['item_{{ $item->id }}'] = {
                  'id':'{{ $item->id }}',
                  'current_status':'{{ $item->status }}',
                };
              </script>

              <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $item->item()->first()->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->amount, 2) }}</td>

                <!-- set progress status -->
                <td>
                  <select id="status_item_{{ $item->id }}" class="form-control" onchange="change_status({{ $item->id }})">
                    @foreach(App\Models\Item::getStatusAll() as $key => $value )
                      @php
                        $print_option = true;

                        if($key == App\Models\Item::getStatus('reject') or $key == App\Models\Item::getStatus('received')){
                          $print_option = false;
                        }

                        if($item->status != App\Models\Item::getStatus('queue') and $key == App\Models\Item::getStatus('queue')){
                          $print_option = false;
                        }

                        if($item->status == App\Models\Item::getStatus('sending') and $key != App\Models\Item::getStatus('sending')){
                          $print_option = false;
                        }
                      @endphp

                      @if($print_option)
                        <option value="{{ $key }}"
                          @if($item->status == $key) selected @endif
                          @if($item->status == App\Models\Item::getStatus('sending')) disabled @endif
                        >{{ $value }}</option>
                      @endif
                    @endforeach
                  </select>
                </td>
                <!-- end progress status -->
              </tr>
            @endforeach
          @enddata_table
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <!-- reject order modal -->
    @modal(['modal_id' => 'reject_modal'])
      @slot('modal_title')
        Reject Order
      @endslot

      @slot('modal_body')
        <p>Are you sure you want to reject order no. <br> {{ $order->order_no }}</p>
        <form method="post" class="d-none" id="reject_order_form"
          action="{{ route('admin.orders.destroy', ['order' => $order]) }}"
        >
          @csrf
          @method('DELETE')
        </form>
      @endslot

      @slot('modal_footer')
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger" form="reject_order_form">Reject</button>
      @endslot
    @endmodal


    <!-- save order modal -->
    @modal(['modal_id' => 'save_modal'])
      @slot('modal_title')
        Save Details
      @endslot

      @slot('modal_body')
        <p>Are you sure you save details of order no. <br> {{ $order->order_no }} </p>
        <form method="post" class="d-none" id="save_order_form"
          action="{{ route('admin.orders.update', ['order' => $order]) }}"
        >
          @csrf
          @method('PUT')
          <input type="hidden" name="status" id="items_status">
        </form>
      @endslot

      @slot('modal_footer')
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" form="save_order_form">Save</button>
      @endslot
    @endmodal

  </div>
@endsection

@section('page_js')
  @parent

  <script type="text/javascript">
    function on_reject() {
      $('#reject_modal').modal('show');
    }

    function on_save() {
      $('#save_modal').modal('show');
    }

    function change_status(item_id) {
      var status = $('#status_item_' + item_id).val();

      items_status['item_' + item_id]['changed_status'] = status;
      $('#items_status').val(JSON.stringify(items_status));
    }
  </script>

  <script type="text/javascript">
    $(function () {
      $('#order_total').text('{{ number_format($order_total, 2)}}');
      $('#items_status').val(JSON.stringify(items_status));
    })
  </script>
@endsection
