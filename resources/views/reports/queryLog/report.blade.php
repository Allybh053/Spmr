<div class="container-xl px-4 mt-4" id="reportBox" style="display: none;">
  <div class="row">
    <div class="col-xl-12">
      <div id="report-dt-div">
        <div class="card mb-4">
          <div class="card-header">
            <div class="row">
              <div class="col-md-12" style="text-align:right;">
                <button type="button" class="btn btn-sm btn-info" id="back" style="float: left">Back</button>
                @can('query_log_download')
                <button id="exportExcel" class="exportReport btn btn-sm btn-success">EXCEL</button>
                <button id="exportCSV" class="exportReport btn btn-sm btn-primary">CSV</button>
                <button id="exportPDF" class="exportReport btn btn-sm btn-danger">PDF</button>
                @endcan
              </div>
            </div>
          </div>
          <div class="card-body mt-1 mb-4">
            <div class="row">
                  <div class="table-responsive">
                  <table id="senderEmailDatatable" class="fixed_header display table table-striped table-bordered" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Sender Ip</th>
                          <th>From Address</th>
                          <th>To Address</th>
                          <th>Email Subject</th>
                          <th>MTA Outbound ID/IP</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>             
                      </tbody>
                    </table>
                  </div>
            </div>


          </div>

        </div>
      </div>
    </div>
  </div>
</div>