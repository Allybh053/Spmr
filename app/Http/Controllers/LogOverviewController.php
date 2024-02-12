<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogOverviewController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $serverName = [
            "serverLocal" => "Server Local",
            "serverFirst" => "Server First",
            "serverSecond" => "Server Second"
        ];

         // Sample data
    $serverData = [
        "serverName" => "Server Second",
        "ipAddress" => "10.0.0.1",
        "serverStatus" => "DOWN",
        "serviceStatus" => "DOWN",
        "sasl" => "5",
        "smtpdLost" => "10.123",
        "smtpdTimeOut" => "3.678",
        "bounced" => "8",
        "messagesChart" => [
            "received" => [
                "data1" => "15",
                "data2" => "45.45%"
            ],
            "deferred (52 defferals)" => [
                "data1" => "3",
                "data2" => "9.09%"
            ],
            "delivered" => [
                "data1" => "9",
                "data2" => "27.27%"
            ],
            "bounced" => [
                "data1" => "6",
                "data2" => "18.18%"
            ],
            "receipients" => [
                "data1" => "4",
                "data2" => "12.12%"
            ],
            "sending host/domains" => [
                "data1" => "2",
                "data2" => "6.06%"
            ]
        ],
        "top10Table" => [
            [
                "host" => "10.0.0.1",
                "receipientEmail" => "x@x.com",
                "senderEmail" => "y@y.com"
            ],
            [
                "host" => "10.0.0.1",
                "receipientEmail" => "z@z.com",
                "senderEmail" => "q@q.com"
            ],
            [
                "host" => "10.0.0.1",
                "receipientEmail" => "k@k.com",
                "senderEmail" => "l@l.com"
            ],
            [
                "host" => "10.0.0.1",
                "receipientEmail" => "m@m.com",
                "senderEmail" => "n@n.com"
            ]
        ],
        "trafficSummary" => [
            [
                "message" => "bounced",
                "data1" => "8",
                "data2" => "20.00%",
                "data3" => "2.33%",
            ],
            [
                "message" => "delivered",
                "data1" => "9",
                "data2" => "22.50%",
                "data3" => "2.33%",
            ],
            [
                "message" => "receipients",
                "data1" => "4",
                "data2" => "10.00%",
                "data3" => "2.33%",
            ],
            [
                "message" => "sendingHost/Domain",
                "data1" => "2",
                "data2" => "5.00%",
                "data3" => "2.33%",
            ]
        ]
    ];

        return view('log.index',
        compact( 'serverName','serverData'));
    }


    public function getNewServerData(Request $request)
    {
        $serverName = $request->serverName;
        $dummyData = $this->dummyData($serverName);
        
        return response()->json($dummyData);
        
    }

    private function dummyData($serverName)
    {
            if ($serverName === "serverLocal") {
                return [
                    "serverName" => "Server Local",
                    "ipAddress" => "127.0.0.1",
                    "serverStatus" => "UP",
                    "serviceStatus" => "UP",
                    "sasl" => "0",
                    "smtpdLost" => "38.889",
                    "smtpdTimeOut" => "4.444",
                    "bounced" => "3",
                    "messagesChart" => [
                        "received" => [
                            "data1" => "4",
                            "data2" => "25.58%"
                        ],
                        "deferred (52 defferals)" => [
                            "data1" => "9",
                            "data2" => "20.93%"
                        ],
                        "delivered" => [
                            "data1" => "8",
                            "data2" => "18.6%"
                        ],
                        "bounced" => [
                            "data1" => "6",
                            "data2" => "13.95%"
                        ],
                        "receipients" => [
                            "data1" => "2",
                            "data2" => "4.65%"
                        ],
                        "sending host/domains" => [
                            "data1" => "1",
                            "data2" => "2.33%",
                        ]
                    ],
                    "top10Table" => [
                        [
                            "host" => "127.0.0.1",
                            "receipientEmail" => "a@a.com",
                            "senderEmail" => "s@s.com"
                        ],
                        [
                            "host" => "127.0.0.1",
                            "receipientEmail" => "a@a.com",
                            "senderEmail" => "s@s.com"
                        ],
                        [
                            "host" => "127.0.0.1",
                            "receipientEmail" => "a@a.com",
                            "senderEmail" => "s@s.com"
                        ],
                        [
                            "host" => "127.0.0.1",
                            "receipientEmail" => "a@a.com",
                            "senderEmail" => "s@s.com"
                        ]
                    ],
                    "trafficSummary" => [
                        [
                            "message" => "bounced",
                            "data1" => "6",
                            "data2" => "13.95%",
                            "data3" => "2.33%",
                        ],
                        [
                            "message" => "delivered",
                            "data1" => "8",
                            "data2" => "18.6%",
                            "data3" => "2.33%",
                        ],
                        [
                            "message" => "receipients",
                            "data1" => "2",
                            "data2" => "4.65%",
                            "data3" => "2.33%",
                        ],
                        [
                            "message" => "sendingHost/Domain",
                            "data1" => "1",
                            "data2" => "2.33%",
                            "data3" => "2.33%",
                        ]
                    ]
                ];
            } elseif ($serverName === "serverFisrt") {
                return [
                    "serverName" => "Server First",
                    "ipAddress" => "192.168.1.1",
                    "serverStatus" => "UP",
                    "serviceStatus" => "DOWN",
                    "sasl" => "2",
                    "smtpdLost" => "20.123",
                    "smtpdTimeOut" => "5.678",
                    "bounced" => "6",
                    "messagesChart" => [
                        "received" => [
                            "data1" => "10",
                            "data2" => "40.00%"
                        ],
                        "deferred (52 defferals)" => [
                            "data1" => "5",
                            "data2" => "20.00%"
                        ],
                        "delivered" => [
                            "data1" => "8",
                            "data2" => "32.00%"
                        ],
                        "bounced" => [
                            "data1" => "3",
                            "data2" => "12.00%"
                        ],
                        "receipients" => [
                            "data1" => "2",
                            "data2" => "8.00%"
                        ],
                        "sending host/domains" => [
                            "data1" => "1",
                            "data2" => "4.00%"
                        ]
                    ],
                    "top10Table" => [
                        [
                            "host" => "192.168.1.1",
                            "receipientEmail" => "b@b.com",
                            "senderEmail" => "t@t.com"
                        ],
                        [
                            "host" => "192.168.1.1",
                            "receipientEmail" => "c@c.com",
                            "senderEmail" => "u@u.com"
                        ],
                        [
                            "host" => "192.168.1.1",
                            "receipientEmail" => "d@d.com",
                            "senderEmail" => "v@v.com"
                        ],
                        [
                            "host" => "192.168.1.1",
                            "receipientEmail" => "e@e.com",
                            "senderEmail" => "w@w.com"
                        ]
                    ],
                    "trafficSummary" => [
                        [
                            "message" => "bounced",
                            "data1" => "6",
                            "data2" => "12.00%",                           
                            "data3" => "2.33%",
                        ],
                        [
                            "message" => "delivered",
                            "data1" => "8",
                            "data2" => "16.00%",
                            "data3" => "2.33%",
                        ],
                        [
                            "message" => "receipients",
                            "data1" => "2",
                            "data2" => "4.00%",
                            "data3" => "2.33%",
                        ],
                        [
                            "message" => "sendingHost/Domain",
                            "data1" => "1",
                            "data2" => "2.00%",
                            "data3" => "2.33%",
                        ]
                    ]
                ];
            }elseif ($serverName === "serverSecond") {
                return [
                    "serverName" => "Server Second",
                    "ipAddress" => "10.0.0.1",
                    "serverStatus" => "DOWN",
                    "serviceStatus" => "DOWN",
                    "sasl" => "5",
                    "smtpdLost" => "10.123",
                    "smtpdTimeOut" => "3.678",
                    "bounced" => "8",
                    "messagesChart" => [
                        "received" => [
                            "data1" => "15",
                            "data2" => "45.45%"
                        ],
                        "deferred (52 defferals)" => [
                            "data1" => "3",
                            "data2" => "9.09%"
                        ],
                        "delivered" => [
                            "data1" => "9",
                            "data2" => "27.27%"
                        ],
                        "bounced" => [
                            "data1" => "6",
                            "data2" => "18.18%"
                        ],
                        "receipients" => [
                            "data1" => "4",
                            "data2" => "12.12%"
                        ],
                        "sending host/domains" => [
                            "data1" => "2",
                            "data2" => "6.06%"
                        ]
                    ],
                    "top10Table" => [
                        [
                            "host" => "10.0.0.1",
                            "receipientEmail" => "x@x.com",
                            "senderEmail" => "y@y.com"
                        ],
                        [
                            "host" => "10.0.0.1",
                            "receipientEmail" => "z@z.com",
                            "senderEmail" => "q@q.com"
                        ],
                        [
                            "host" => "10.0.0.1",
                            "receipientEmail" => "k@k.com",
                            "senderEmail" => "l@l.com"
                        ],
                        [
                            "host" => "10.0.0.1",
                            "receipientEmail" => "m@m.com",
                            "senderEmail" => "n@n.com"
                        ]
                    ],
                    "trafficSummary" => [
                        [
                            "message" => "bounced",
                            "data1" => "8",
                            "data2" => "20.00%",
                            "data3" => "2.33%",
                        ],
                        [
                            "message" => "delivered",
                            "data1" => "9",
                            "data2" => "22.50%",
                            "data3" => "2.33%",
                        ],
                        [
                            "message" => "receipients",
                            "data1" => "4",
                            "data2" => "10.00%",
                            "data3" => "2.33%",
                        ],
                        [
                            "message" => "sendingHost/Domain",
                            "data1" => "2",
                            "data2" => "5.00%",
                            "data3" => "2.33%",
                        ]
                    ]
                ];
            } else {
                // Handle other server names if needed
                return [];
            }
    }


}
