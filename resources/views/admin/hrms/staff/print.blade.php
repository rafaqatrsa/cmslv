<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }}</title>
        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
                margin: 24px;
                color: #111827;
            }

            .sheet {
                max-width: 860px;
                margin: 0 auto;
                border: 1px solid #d1d5db;
                padding: 24px;
            }

            .heading {
                display: flex;
                justify-content: space-between;
                align-items: start;
                margin-bottom: 18px;
                border-bottom: 2px solid #1f4da0;
                padding-bottom: 12px;
            }

            .heading h1 {
                margin: 0;
                font-size: 24px;
            }

            .heading p {
                margin: 4px 0 0;
                font-size: 13px;
                color: #4b5563;
            }

            .grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px 24px;
                margin-bottom: 18px;
            }

            .row strong {
                display: block;
                font-size: 12px;
                text-transform: uppercase;
                color: #6b7280;
                margin-bottom: 4px;
            }

            .row span {
                font-size: 14px;
            }

            .body-copy {
                font-size: 14px;
                line-height: 1.7;
                white-space: pre-line;
            }

            @media print {
                body {
                    margin: 0;
                }

                .sheet {
                    border: 0;
                }
            }
        </style>
    </head>
    <body onload="window.print()">
        <div class="sheet">
            <div class="heading">
                <div>
                    <h1>{{ $title }}</h1>
                    <p>TNT SOL HRMS Staff Directory</p>
                </div>
                <div style="text-align:right">
                    <strong>Date</strong>
                    <div>{{ now()->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="grid">
                <div class="row">
                    <strong>Name</strong>
                    <span>{{ $staff['full_name'] }}</span>
                </div>
                <div class="row">
                    <strong>Staff ID</strong>
                    <span>{{ $staff['employee_id'] }}</span>
                </div>
                <div class="row">
                    <strong>Role</strong>
                    <span>{{ $staff['role_name'] }}</span>
                </div>
                <div class="row">
                    <strong>Branch</strong>
                    <span>{{ $staff['branch_name'] }}</span>
                </div>
                <div class="row">
                    <strong>Department</strong>
                    <span>{{ $staff['department_name'] }}</span>
                </div>
                <div class="row">
                    <strong>Designation</strong>
                    <span>{{ $staff['designation_name'] }}</span>
                </div>
                <div class="row">
                    <strong>Date of Joining</strong>
                    <span>{{ $staff['date_of_joining_label'] }}</span>
                </div>
                <div class="row">
                    <strong>Mobile No</strong>
                    <span>{{ $staff['mobile_no'] }}</span>
                </div>
            </div>

            <div class="body-copy">{{ $body }}</div>
        </div>
    </body>
</html>
