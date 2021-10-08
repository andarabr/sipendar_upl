@extends('layouts.main')

@section('content')

		<div class="p-3 bg-white bborder">
			<div class="row">
				<div class="col-md-12 col-xl-12">
					<div class="py-4 d-flex justify-content-end align-items-center">
						<h3 class="mr-auto">List Nama PPATK</h3>
						<a class="btn btn-warning mr-2 btn-sm" href="{{ route('ppatk.download.format') }}">
							Download Format
						</a>
						<button type="button" class="btn btn-primary mr-2 btn-sm" data-toggle="modal" data-target="#importExcel">
							Import CSV
						</button>
						<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteData">
							Delete All
						</button>
					</div>


					@if (session()->has('message'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						{{session()->get('message')}}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					@endif
                    {!! $dataTable->table(['class' =>'table table-striped table-bordered']) !!}
				</div>
			</div>
            {!! $dataTable->scripts() !!}
		</div>



		<!-- Import Excel -->
		<div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form method="post" action="{{ route('namelist.import') }}" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Import CSV</h5>
						</div>
						<div class="modal-body">

							{{ csrf_field() }}

							<label>Pilih file CSV</label>
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

		{{-- Delete All --}}
		<div class="modal fade" id="deleteData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form action="{{ route('namelist.destroy') }}" method="POST">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
						</div>
						<div class="modal-body">
							{{ csrf_field() }}
							{{method_field('delete')}}
							<h6 class="text-center">Apakah Anda Yakin Ingin Menghapus Semua Data?</h6>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-danger btn-sm">Delete</button>
						</div>
					</div>
				</form>
			</div>
		</div>

@endsection
