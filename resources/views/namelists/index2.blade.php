@extends('layouts.main')

@section('content')

		<div class="container pt-4 bg-white">
			<div class="row">
				<div class="col-md-12 col-xl-12">
					<div class="py-4 d-flex justify-content-end align-items-center">
						<h1 class="mr-auto">Name List</h1>
						<button type="button" class="btn btn-primary mr-5" data-toggle="modal" data-target="#importExcel">
							Import CSV
						</button>
						<a class="btn btn-danger" href="{{ route('namelist.destroy') }}">Delete All</a>
					</div>


					@if (session()->has('message'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						{{session()->get('message')}}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					@endif
                    {!! $dataTable->table() !!}
				</div>
			</div>
		</div>



		<!-- Import Excel -->
		<div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form method="post" action="{{ route('namelist.import') }}" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
						</div>
						<div class="modal-body">

							{{ csrf_field() }}

							<label>Pilih file excel</label>
							<div class="form-group">
								<input type="file" name="file" required="required">
							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Import</button>
						</div>
					</div>
				</form>
			</div>
		</div>
@endsection
