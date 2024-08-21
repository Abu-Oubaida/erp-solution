@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('fixed.asset.show')}}" class="text-capitalize text-chl">Fixed Asset</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>

        </div>
        <div class="row">
            <div class="col-md-2 mb-1">
                <label for="project">Enter Project Name<span class="text-danger">*</span></label>
                <select id="project" name="project" class="select-search cursor-pointer">
                    <option value="">Pick a state...</option>
                    @if(count($projects))
                        @foreach($projects as $p)
                            <option @if(Request::get('project') !== null && Request::get('project') == $p->projects->id)selected @endif value="{!! $p->projects->id !!}">{!! $p->projects->branch_name !!}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-2 mb-1">
                <label for="ref">Reference No<span class="text-danger">*</span></label>
                <input class="form-control" value="{!! Request::get('ref') !!}" type="text" placeholder="Reference number..." id="ref-src">
            </div>

            <div class="col-md-2 mb-1">
                <label for="r_type">Reference Type<span class="text-danger">*</span></label>
                <select id="r_type" name="r_type" class="select-search cursor-pointer">
                    <option value="">Pick a state...</option>
                    @if(count($ref_types))
                        @foreach($ref_types as $rt)
                            <option @if(Request::get('rt') !== null && Request::get('rt') == $rt->id)selected @endif value="{!! $rt->id !!}">{!! $rt->name !!}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2 mt-4">
                <button class="btn btn-chl-outline" type="button" id="ref-src-btn" onclick="return Obj.fixedAssetOpeningSearch(this,'fixed-asset-body')">
                    <i class="fa fa-search"></i> search
                </button>
            </div>
            <div id="fixed-asset-body">
                @if(isset($fixed_asset_with_ref_report_list))
                    @include('back-end.asset._fixed_asset_opening_project_wise_list')
                @endif
                @if((isset($fixed_assets) && isset($withRefData)) )
                    @include('back-end.asset._fixed_asset_opening_body')
                @endif
            </div>
        </div>

    </div>
    <script type="module">
        import {
            DataTable,
            exportJSON,
            exportCSV,
            exportTXT,
            exportSQL
        } from "https://fiduswriter.github.io/simple-datatables/demos/dist/module.js"


        const exportCustomCSV = function(dataTable, userOptions = {}) {
            // A modified CSV export that includes a row of minuses at the start and end.
            const clonedUserOptions = {
                ...userOptions
            }
            clonedUserOptions.download = false
            const csv = exportCSV(dataTable, clonedUserOptions)
            // If CSV didn't work, exit.
            if (!csv) {
                return false
            }
            const defaults = {
                download: true,
                lineDelimiter: "\n",
                columnDelimiter: ";"
            }
            const options = {
                ...defaults,
                ...clonedUserOptions
            }
            const separatorRow = Array(dataTable.data.headings.filter((_heading, index) => !dataTable.columns.settings[index]?.hidden).length)
                .fill("-")
                .join(options.columnDelimiter)
            const str = `${separatorRow}${options.lineDelimiter}${csv}${options.lineDelimiter}${separatorRow}`
            if (userOptions.download) {
                // Create a link to trigger the download
                const link = document.createElement("a")
                link.href = encodeURI(`data:text/csv;charset=utf-8,${str}`)
                link.download = `${options.filename || "datatable_export"}.txt`
                // Append the link
                document.body.appendChild(link)
                // Trigger the download
                link.click()
                // Remove the link
                document.body.removeChild(link)
            }
            return str
        }
        const table = new DataTable("#datatablesSimple")
        document.getElementById("export-csv").addEventListener("click", () => {
            exportCSV(table, {
                download: true,
                lineDelimiter: "\n",
                columnDelimiter: ","
            })
        })
        document.getElementById("export-sql").addEventListener("click", () => {
            exportSQL(table, {
                download: true,
                tableName: "export_table"
            })
        })
        document.getElementById("export-txt").addEventListener("click", () => {
            exportTXT(table, {
                download: true
            })
        })
        document.getElementById("export-json").addEventListener("click", () => {
            exportJSON(table, {
                download: true,
                space: 3
            })
        })
        document.getElementById("export-custom").addEventListener("click", () => {
            exportCustomCSV(table, {
                download: true
            })
        })
    </script>
@stop

