<div class="modal-body" id="mediumBody">
    <div class="form-row mb-4">
      <table class="w-100">
        <tr>
          <td style="width: 30%">Tanggal Transaksi</td>
          <td style="width: 5%">:</td>
          <td>{{ \Carbon\Carbon::parse($result->date_transactions)->format('d/m/Y')}}</td>
        </tr>
        <tr>
          <td style="width: 30%">Item</td>
          <td style="width: 5%">:</td>
          <td>{{$result->item_transactions}}</td>
        </tr>
        <tr>
          <td style="width: 30%">Status</td>
          <td style="width: 5%">:</td>
          <td>
            <span class="{{$result->status == '0' ? 'badge badge-warning' : 'badge badge-info'}}">
              {{$result->status == '0' ? 'In Progress' : 'Done'}}
            </span> 
          </td>
        </tr>
      </table>
    </div>
</div>
<div class="modal-footer">
    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
</div>
