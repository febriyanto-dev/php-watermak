@extends('layouts.app')

@section('css_additional')

@endsection

@section('content')

<div class="container-fluid px-4">
    <div class="row">

        <div class="col-xl-12 col-md-12 mt-5">
            <div class="statusMsg"></div>
        </div>

        <div class="col-xl-12 col-md-12">

            <div class="card mb-4">

                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Watermak
                </div>

                <div class="card-body">
                    <form id="FormAction" enctype="multipart/form-data">
                        <input type="hidden" name="module" value="watermak">
                        <input type="hidden" name="act" value="add">

                        <div class="form-floating mb-3">
                            <input class="form-control name" id="inputEmail" type="text" name="name" placeholder="Name" />
                            <label for="inputEmail">Name</label>
                        </div>

                        <div class="form-group mb-3">
                            <input class="form-control form-control-lg image" multiple="" type="file" name="images[]" >
                        </div>

                        <div class="mt-4 mb-0">
                            <div class="d-grid"><button type="submit" class="btn btn-primary btn-block submitBtn">Submit</button></div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('plugin')
<script src="{{ asset('js/pages/home/plugin.js?v='.gitVersion())}}"></script>
@endsection