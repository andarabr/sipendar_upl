@extends('layouts.main')

@section('content')

		<div class="p-3 bg-white bborder">
			<div class="row">
				<div class="col-md-12 col-xl-12">
					<div class="py-4 d-flex justify-content-end align-items-center">
						<h3 class="mr-auto">List Nama yang tidak tersedia di Aither</h3>
                        {{-- <a class="btn btn-info btn-sm"
						@if ($customers->count() > 0)
							href="{{ route('customer.xmlall', $cust = 'C') }}">
						@else
							href=# onclick='swal("Data Kosong", "Data Kosong, tidak bisa menyimpan XML!", "error");'>
						@endif
						Save All XML</a> --}}
					</div>

					@if (session()->has('message'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						{{session()->get('message')}}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					@endif

                    {!! $dataTable->table(['class' =>'table table-striped table-bordered table-responsive']) !!}


					{{-- <table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama</th>
								<th>No Identitas</th>
                                <th>Tanggal Lahir</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($customers as $customer)
							<tr>
								<td>{{$loop->iteration + $customers->firstItem() - 1}}</td>
								<td>{{$customer->name}}</td>
								<td>{{$customer->id_num}}</td>
                                <td>{{$customer->birthdate}}</td>
							</tr>
							@empty
								<td colspan="6" class="text-center">Data Tidak Tersedia</td>
							@endforelse
						</tbody>
					</table>
					{{$customers->links()}} --}}
				</div>
			</div>
            {!! $dataTable->scripts() !!}
		</div>

@endsection
