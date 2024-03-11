<div class="container-xl px-4 mt-4" id="reportBox" style="display: none;">
  <div class="row">
    <div class="col-xl-12">
      <div id="report-dt-div">
        <div class="card mb-4">
          <div class="card-header">
            <div class="row">
              <div class="col-md-12" style="text-align:right;">
                <button type="button" class="btn btn-sm btn-info" id="back" style="float: left">Back</button>
                <button id="exportExcel" class="exportReport btn btn-sm btn-success">EXCEL</button>
                <button id="exportCSV" class="exportReport btn btn-sm btn-primary">CSV</button>
                <button id="exportPDF" class="exportReport btn btn-sm btn-danger">PDF</button>
              </div>
            </div>
          </div>
          <div class="card-body mt-1 mb-4">
            <div class="row">
              <div class="card mb-4">
                <div class="card-header">Summary</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="spmrTrafficDatatable" class="display" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <td style="padding-left:0px; text-align:left;" width="130px">Reported Period</td>
                          <td style="padding-left:0px; text-align:left;" width="250px"></td>
                        </tr>
                        <tr>
                          <td style="padding-left:0px; text-align:left;" width="130px">First</td>
                          <td style="padding-left:0px; text-align:left;" width="250px"></td>
                        </tr>
                        <tr>
                          <td style="padding-left:0px; text-align:left;" width="130px">Last</td>
                          <td style="padding-left:0px; text-align:left;" width="250px"></td>
                        </tr>
                      </thead>
                      <tr style="height: 20px;"></tr>
                      <thead>
                        <tr style="background-color:#ded2d2;">
                          <td colspan="3"></td>
                          <td style="text-align:center;">Mails</td>
                          <td style="text-align:center;">Size
                          <td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td colspan="3">Mails successfully sent </td>
                          <td style="text-align:center;"></td>
                          <td style="text-align:center;"></td>
                        </tr>
                        <tr>
                          <td colspan="3">Mails failed/refused</td>
                          <td style="text-align:center;"></td>
                          <td style="text-align:center;"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
            <div class="col-lg-12">
              <!-- Pie chart example-->
              <div class="card mb-4">
                <div class="card-header">Daily History</div>
                <div class="card-body">
                  <div class="chart-pie">
                    <canvas id="myBarChartDaily" width="100%" height="50"></canvas>
                  </div>
                </div>
                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="card mb-4" style="max-height: 394px;min-height: 394px;">
                <div class="card-header">Daily History Statistic Table</div>
                <div class="card-body">
                  <div class="table-responsive tableFixHead" >
                    <table id="dailyHistoryDatatable" class="fixed_header display table table-striped table-bordered" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Day</th>
                          <th>Mail</th>
                          <th>Size</th>
                        </tr>
                      </thead>
                      <tbody>             
                      </tbody>
                      <tfoot>
                        <tr bgcolor="#ECECEC">
                            <th>Average</th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr bgcolor="#ECECEC">
                            <th>Total</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

            <div class="row">
            <div class="col-lg-6">
              <!-- Pie chart example-->
              <div class="card mb-4">
                <div class="card-header">Monthly History</div>
                <div class="card-body">
                  <div class="chart-pie">
                    <canvas id="myBarChartMonthly" width="100%" height="50"></canvas>
                  </div>
                </div>
                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="card mb-4" style="max-height: 394px;min-height: 394px;">
                <div class="card-header">Monthly History Statistic Table</div>
                <div class="card-body">
                  <div class="table-responsive tableFixHead" >
                    <table id="monthlyHistoryDatatable" class="fixed_header display table table-striped table-bordered" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Month</th>
                          <th>Mail</th>
                          <th>Size</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                      <tr><th bgcolor="#ECECEC">Total</th><th bgcolor="#ECECEC"></th><th bgcolor="#ECECEC"></th></tr>
                    </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-lg-6">
              <!-- Pie chart example-->
              <div class="card mb-4">
                <div class="card-header">Yearly History</div>
                <div class="card-body">
                  <div class="chart-pie">
                    <canvas id="myBarChartYearly" width="100%" height="50"></canvas>
                  </div>
                </div>
                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="card mb-4" style="max-height: 394px;min-height: 394px;">
                <div class="card-header">Yearly History Statistic Table</div>
                <div class="card-body">
                  <div class="table-responsive tableFixHead" >
                    <table id="yearlyHistoryDatatable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Year</th>
                          <th>Mail</th>
                          <th>Size</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                      <tr><th bgcolor="#ECECEC">Total</th><th bgcolor="#ECECEC"></th><th bgcolor="#ECECEC"></th></tr>
                    </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>



          </div>

        </div>
      </div>
    </div>
  </div>
</div>