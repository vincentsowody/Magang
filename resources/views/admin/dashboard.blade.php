@extends('layouts.admin')

@section('content')

@include('admin.partials.sidebar')

<main class="main-content">

    @include('admin.partials.topbar')

    @include('admin.views.dashboard-view')
    @include('admin.views.candidates-view')
    @include('admin.views.documents-view')
    @include('admin.views.report-view')

</main>

@include('admin.modals.doc-review-modal')
@include('admin.modals.upload-modal')
@include('admin.modals.logout-modal')
@include('admin.modals.reg-modal')
@include('admin.modals.success-modal')
@include('admin.modals.review-modal')
@include('admin.modals.placement-modal')
@include('admin.modals.import-modal')

@endsection