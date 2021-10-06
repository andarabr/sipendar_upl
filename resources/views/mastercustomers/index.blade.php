@extends('layouts.main')

@section('content')

		<div class="p-3 bg-white bborder">
			<div class="row">
				<div class="col-12">
					<div class="py-4 d-flex justify-content-end align-items-center">
						<h3 class="mr-auto">Master CBS Data</h3>
						<button type="button" class="btn btn-primary mr-5 btn-sm" data-toggle="modal" data-target="#importExcel">
							Import CSV
						</button>
						<a class="btn btn-danger btn-sm" href="{{ route('namelist.destroy') }}">Delete All</a>
					</div>


					@if (session()->has('message'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						{{session()->get('message')}}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					@endif

					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama</th>
								<th>No Identitas / NPWP</th>
                                {{-- <th>Action</th> --}}
							</tr>
						</thead>
						<tbody>
							@forelse ($customers as $customer)
							<tr>
								<td>{{$loop->iteration + $customers->firstItem() - 1}}</td>
								<td>{{$customer->name}}</td>
								<td>
									@if(is_null($customer->id_num))
										{{$customer->npwp}}
									@else
										{{$customer->id_num}}
									@endif
								</td>
                                {{-- <td><a href="{{ route('customer.xmldown', $customer->id)}}" class="edit btn btn-info btn-sm">XML</a></td> --}}
							</tr>
							@empty

							@endforelse
						</tbody>
					</table>
					{{$customers->links()}}
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
