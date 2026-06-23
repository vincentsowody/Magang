@extends('layouts.admin')

@section('content')

@include('admin.partials.sidebar')

<!-- ══ MAIN AREA ══ -->
<main style="flex:1;display:flex;flex-direction:column;overflow:hidden;min-width:0">

    @include('admin.partials.topbar')

    @include('admin.views.dashboard-view')
    @include('admin.views.documents-view')
    @include('admin.views.report-view')

</main><!-- /main -->

@include('admin.modals.doc-review-modal')
@include('admin.modals.upload-modal')
@include('admin.modals.logout-modal')
@include('admin.modals.reg-modal')
@include('admin.modals.success-modal')
@include('admin.modals.review-modal')
<!-- Form tambahan untuk penempatan saat diterima -->
@include('admin.modals.placement-modal')
@include('admin.modals.import-modal')