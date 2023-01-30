@extends('layouts.dashboard-frontend')

@section('page-content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">
        <!-- container-fluid -->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row begin">
                <div class="col-lg-12">
                    <div class="card account-head mb-4">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="py-2">
                                    <h4 class="font-600">WhatsApp Automation</h4>
                                    <p>
                                        Send instant, scheduled or automated messages to your contact
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="">
                                    <div class="all-create">
                                        <a href="{{route('user.send.broadcast', Auth::user()->username)}}">
                                            <button>
                                                Send Brodcast Messsage
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
<<<<<<< HEAD
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-tabs-custom">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#">New Campaign</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('user.receive.message', Auth::user()->username)}}">Recieved Messages</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Sent Campaigns</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('user.auto.reply', Auth::user()->username)}}">Auto Reply</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">View Broadcast </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Settings</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="Edit">
                            <div class="form">
                                <div class="row">
                                    <p class="tell mb-4">
                                        <b>
                                            Sender Accounts
                                        </b> <br>
                                        <span>
                                            Add one or more whatsapp number to start your automation
                                        </span>
                                    </p>
                                    <div class="col-lg-12">
                                        <label>Whatsapp Number</label>
                                        <div class="row">
                                            <div class="col-md-12 mb-4">
                                                <input type="tel" placeholder="+234 800 000 0000" name="name" class="input" required>
                                            </div>
=======
                        {{-- <div class="py-2">
                            <h4 class="font-600">WhatsApp Automation</h4>
                            <p>
                                Send instant, scheduled or automated messages to your contact
                            </p>
                            <div class="">
                                <div class="all-create">
                                    <button>
                                        <a href="{{route('user.send.broadcast', Auth::user()->username)}}">
                                            Send Brodcast Messsage
                                        </a>
                                    </button>
                                </div>
                            </div>
                        </div> --}}
                        <div class="d-flex account-nav">
                            {{-- <p class="ps-0">New Campaign</p> --}}
                            {{-- <p>
                                <a href="#" class="text-decoration-none text-dark">Recieved Messages</a>
                            </p> --}}
                            <p class="ps-0 active">
                                <a href="#" class="text-decoration-none text-dark">Campaign Lists</a>
                            </p>
                            {{-- <p>
                                <a href="#" class="text-decoration-none text-dark">Auto Reply</a>
                            </p> --}}
                            {{-- <p>

                            </p> --}}
                            {{-- <p class="ps-0 active">
                                <a href="#" class="text-decoration-none text-dark">Settings</a>
                            </p> --}}
                        </div>
                        <div class="acc-border"></div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
                <div class="">
                    <div class="store-table">
                        <div class="table-head row pt-4">
                            <div class="col-lg-12">
                                <h4>Whatsapp Campaigns</h4>
                            </div>
                            <!-- <div class="col-lg-6 search-item">
                                <div class="bg-light search-store border-in flex">
                                    <input class="bg-light" type="search" placeholder="search by name" name="store" id="" />
                                    <button><i class="bi bi-search"></i></button>
                                </div>
                            </div> -->
                        </div>
                        <div class="table-body mt-5 table-responsive">
                            <table id="datatable-buttons" class=" table table-bordered dt-responsive nowrap w-100">
                                <thead class="fw-bold bg-light rounded-pill ">
                                    <tr>
                                        <th scope="col">S/N</th>
                                        <th scope="col">Campaign Name</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Sent</th>
                                        <th scope="col">Failed</th>
                                        <th scope="col">Campaign Type</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                        <!-- <th scope="col">Opens</th> -->
                                        {{-- <th scope="col">Unsubscribed</th> --}}
                                    </tr>
                                </thead>
                                @if($whatsappAutomations->isEmpty())
                                    <tbody>
                                        <tr>
                                            <td class="align-enter text-dark font-15" colspan="8">No sms campaign added.</td>
                                        </tr>
                                    </tbody>
                                @else
                                @foreach($whatsappAutomations as $key => $campaign)
                                <tbody>
                                    <tr>
                                        <th scope="row">{{$loop->iteration}}</th>
                                        <td>
                                            <p class='text-bold-600'> {{$campaign->title}} </p>
                                            <p class='text-muted'>Created at: {{$campaign->created_at->toDayDateTimeString()}}</p>
                                        </td>
                                        <td>
                                            {{ $campaign->readCache('ContactCount') }}
                                        </td>
                                        <td>
                                            {{ $campaign->readCache('DeliveredCount') }}
                                        </td>
                                        <td>
                                            {{ $campaign->readCache('FailedDeliveredCount') }}
                                        </td>
                                        <td>
                                            {!!$campaign->getCampaignType()!!}
                                        </td>
                                        <td>
                                            {!!$campaign->getStatus()!!}
                                        </td>
                                        <td>
                                            <div class="dropdown dropstart">
                                                <button class="btn-list dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="true">
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            Overview
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" type="button" >
                                                            Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" type="button" >
                                                            Pause
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" type="button">
                                                            Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        {{-- <td>{{$smsAutomation->created_at->toDayDateTimeString()}}</td>
                                        <td>{{$smsAutomation->sms_sent}}</td>
                                        <td>{{$smsAutomation->delivered}}</td>
                                        <td>{{$smsAutomation->not_delivered}}</td>
                                        <!-- <td>{{$smsAutomation->opens}}</td> -->
                                        <td>{{$smsAutomation->unsubscribed}}</td> --}}
                                    </tr>
                                </tbody>
                                @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-8">
                    <div class="Edit">
                        <div class="form">
                            <div class="row">
                                <p class="tell mb-4">
                                    <b>
                                        Sender Accounts
                                    </b> <br>
                                    <span>
                                        Add one or more whatsapp number to start your automation
                                    </span>
                                </p>
                                <div class="col-lg-12">
                                    <label>Whatsapp Number</label>
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <input type="tel" placeholder="+234 800 000 0000" name="name" class="input" required>
>>>>>>> 7207286e5441ec516159c115bbccc604a6c73443
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-md-8"></div>
                                            <div class="col-md-4">
                                                <div class="boding">
                                                    <button data-bs-toggle="modal" data-bs-target="#emailConfirm">
                                                        Add New
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="Edit">
                            <div class="form">
                                <div class="row">
                                    <p class="tell mb-4">
                                        <b>
                                            Sending Configuration
                                        </b> <br>
                                        <span>
                                            Reduce the chances of geting blocked by setting the speed for bulk
                                            messages
                                        </span>
                                    </p>
                                    <div class="col-lg-12">
                                        <label>Connection Speed :</label>
                                        <div class="row">
                                            <div class="col-md-12 mb-4">
                                                <select>
                                                    <option>
                                                        Fast
                                                    </option>
                                                    <option> Low </option>
                                                    <option> Medium </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-md-8"></div>
                                            <div class="col-md-4">
                                                <div class="boding">
                                                    <button>
                                                        <a href="" style="color: #fff;">
                                                            Update Setting
                                                        </a>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<<<<<<< HEAD
                </div>
=======
                </div> --}}
                <div class="col-lg-2"></div>
>>>>>>> 7207286e5441ec516159c115bbccc604a6c73443
            </div>
        </div>
    </div>
    <!-- End Page-content -->
    <!-- email confirm modal -->
    <div class="modal fade" id="emailConfirm" tabindex="-1" aria-labelledby="subscribeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        Add Whatsapp Number
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="Edit-level">
                            <div class="form">
                                <div class="col-lg-12">
                                    <label>Phone Number</label>
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <input type="tel" placeholder="+234 800 000 0000" name="name" class="input" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <label>Description</label>
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <textarea name="" placeholder="Enter a description, eg for book sales" id="" cols="30" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-6">
                                        <a href="#" class="text-decoration-none">
                                            <button class="btn px-3" style="color: #714091; border: 1px solid #714091">
                                                Cancel
                                            </button></a>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a href="#" class="text-decoration-none">
                                            <button class="btn px-4" style="color: #ffffff; background-color: #714091">
                                                Save Number
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<<<<<<< HEAD
    <!-- end modal -->

    @endsection
=======
<!-- end modal -->
<style>
    .btn-list{
        border: 0;
        padding: 5px 10px;
        border-radius: 5px;
        color: #7b7676;
    }
    .btn-list .material-icons-outlined{
        font-size: 16px;
    }
    .dropdown-item .material-icons-outlined{
        font-size: 15px;
    }
    .dropdown{
        display: inline;
    }
</style>
@endsection
>>>>>>> 7207286e5441ec516159c115bbccc604a6c73443
