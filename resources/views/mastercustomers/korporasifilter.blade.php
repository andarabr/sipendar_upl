@extends('layouts.main')

@section('content')

{{-- @php
    dump($data);

@endphp  --}}

		<div class="p-3 bg-white bborder" style="border-width: 0 0 1px;">
			<div class="row">
				<div class="col-md-12 col-xl-12">
					<div class="py-4 d-flex justify-content-end align-items-center">
						<h3 class="mr-auto">{{$data['title']}}</h3>
                        {{-- <a class="btn btn-info btn-sm"
						@if ($customers->count() > 0)
							href="{{ route('customer.xmlall', $cust = 'C') }}">
						@else
							href=# onclick='swal("Data Kosong", "Data Kosong, tidak bisa menyimpan XML!", "error");'>
						@endif
						Save All XML</a> --}}
						@if ($customers->count() > 0)
							<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#downloadXML">Save All XML</button>
						@else
						@endif
					</div>
					<hr>

                    <form action="{{ route('korporasi.lookup.filter') }}" method="GET">
                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <label for="inputEmail4">Tambah Filter KTP</label>
                            <select name='filterktp' id='fktpid' class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                                <option
                                @if ($data['fktp'] == 0)
                                    selected
                                @endif
                                value="0">Tidak</option>
                                <option
                                @if ($data['fktp'] == 1)
                                    selected
                                @endif
                                value="1">Ya</option>
                              </select>
                          </div>
                          <div class="form-group col-md-4">
                            <label for="inputPassword4">Tambah Filter Tanggal Lahir</label>
                            <select name='filterbirthdate' id='fbdid' class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                                <option
                                @if ($data['fbd'] == 0)
                                    selected
                                @endif
                                value="0">Tidak</option>
                                <option
                                @if ($data['fbd'] == 1)
                                    selected
                                @endif
                                value="1">Ya</option>
                              </select>
                          </div>
                          <div class="form-group col-md-4">
                            <label for="inputPassword4">Tambah Filter Tempat Lahir</label>
                            <select name='filterbirthplace' id='fbpid' class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                                <option
                                @if ($data['fbp'] == 0)
                                    selected
                                @endif
                                value="0">Tidak</option>
                                <option
                                @if ($data['fbp'] == 1)
                                    selected
                                @endif
                                value="1">Ya</option>
                              </select>
                          </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-right mb-4">Lookup</button>
                    </form>



					@if (session()->has('message'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						{{session()->get('message')}}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					@endif

					<div>
						<br><br>
						<hr>
						<h5 class="text-center">Total Data : {{ $customers->total() }}</h5>
						<hr>
					</div>

					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama</th>
								<th>No Identitas</th>
								<th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
								<th>Jenis Watchlist</th>
								<th>Sumber Watchlist</th>
                                <th style="width: 10%">Action</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($customers as $customer)
							<tr>
								<td>{{$loop->iteration + $customers->firstItem() - 1}}</td>
								<td>{{$customer->name}}</td>
								<td>{{$customer->id_num}}</td>
								<td>{{$customer->birthplace}}</td>
                                <td>{{$customer->birthdate}}</td>
								<form action="{{ route('individu.xml', $customer->id) }}" method="POST">
								@csrf
								<td>
									<input type="hidden" name="id" value="{{$customer->id}}">
									<div class="form-group m-0">
										<select class="form-control" name="tipe_watchlist" id="jenwatch" required>
										  <option value="INTERNAL WATCHLIST">Internal Watchlist</option>
										  <option value="PROAKTIF WATCHLIST">Proaktif Watchlist</option>
										</select>
									</div>
								</td>
								<td>
									<div class="form-group m-0">
										<select class="form-control" name="sumber_watchlist" id="sumwatch" required>
										  <option value="1">Berita</option>
										  <option value="2">Inquiry Apgakum</option>
										  <option value="3">Internal</option>
										  <option value="4">Kasus</option>
										  <option value="5">Pengadilan</option>
										</select>
									</div>
								</td>
                                <td>
                                    {{-- <a href="{{ route('individu.xmldown', $customer->id)}}" class="edit btn btn-primary btn-sm">Detail</a>
                                    <a href="{{ route('individu.xmldown', $customer->id)}}" class="edit btn btn-success btn-sm">Save XML</a> --}}
									<button type="submit" class="edit btn btn-success btn-sm">Save XML</button>
                                </td>
								</form>
							</tr>
							@empty
								<td colspan="8" class="text-center">Data Tidak Tersedia</td>
							@endforelse
						</tbody>
					</table>
					<div class="float-right">
						{{$customers->appends(array(
							'filterktp' => $data['fktp'],
							'filterbirthdate' => $data['fbd'],
							'filterbirthplace' => $data['fbp']
							))->links()}}
					</div>
				</div>
			</div>
		</div>

		<!-- Download XML -->
		<div class="modal fade" onfocus="test()" id="downloadXML" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form method="post" action="{{ route('customer.xmlall', $cust = 'C') }}" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Save All XML</h5>
						</div>
						<div class="modal-body">

							{{ csrf_field() }}
							<input type="hidden" name="cust_type" value="C">
                            <input type="hidden" name="filterktp" id="fktpsend">
                            <input type="hidden" name="filterbirthdate" id="fbdsend">
                            <input type="hidden" name="filterbirthplace" id="fbpsend">
							<div class="form-group">
								<label for="jenwatch">Jenis Watchlist</label>
								<select class="form-control" name="tipe_watchlist" id="jenwatch" required>
								  <option value="INTERNAL WATCHLIST">Internal Watchlist</option>
								  <option value="PROAKTIF WATCHLIST">Proaktif Watchlist</option>
								</select>
							</div>
							<div class="form-group">
								<label for="sumwatch">Sumber Watchlist</label>
								<select class="form-control" name="sumber_watchlist" id="sumwatch" required>
								  <option value="1">Berita</option>
								  <option value="2">Inquiry Apgakum</option>
								  <option value="3">Internal</option>
								  <option value="4">Kasus</option>
								  <option value="5">Pengadilan</option>
								</select>
							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Download</button>
						</div>
					</div>
				</form>
			</div>
		</div>
@endsection
