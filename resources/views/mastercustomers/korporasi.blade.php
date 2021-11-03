@extends('layouts.main')

@section('content')

		<div class="p-3 bg-white bborder">
			<div class="row">
				<div class="col-md-12 col-xl-12">
					<div class="py-4 d-flex justify-content-end align-items-center">
						<h3 class="mr-auto">Lookup Data Proaktif Korporasi by Nama</h3>
						@if ($customers->count() > 0)
							<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#downloadXML">Save All XML Proaktif</button>
                            <button type="button" class="btn btn-info btn-sm ml-2" data-toggle="modal" data-target="#downloadXMLwatchlist">Save All XML Watchlist</button>
						@else
						@endif
					</div>
					<hr>

                    <form action="{{ route('korporasi.lookup.filter') }}" method="GET">
                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <label for="inputEmail4">Tambah Filter KTP</label>
                            <select name='filterktp' class="custom-select my-1 mr-sm-2" id="fktpid">
                                <option selected value="0">Tidak</option>
                                <option value="1">Ya</option>
                                <option value="2">NULL</option>
                              </select>
                          </div>
                          <div class="form-group col-md-4">
                            <label for="inputPassword4">Tambah Filter Tanggal Lahir</label>
                            <select name='filterbirthdate' class="custom-select my-1 mr-sm-2" id="fbdid">
                                <option selected value="0">Tidak</option>
                                <option value="1">Ya</option>
                                <option value="2">NULL</option>
                              </select>
                          </div>
                          <div class="form-group col-md-4">
                            <label for="inputPassword4">Tambah Filter Tempat Lahir</label>
                            <select name='filterbirthplace' id='fbpid' class="custom-select my-1 mr-sm-2" id="fbpid">
                                <option selected value="0">Tidak</option>
                                <option value="1">Ya</option>
                                <option value="2">NULL</option>
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
                                <th>Periode</th>
                                <th>Id</th>
                                <th>Kode Watchlist</th>
                                <th>Jenis Pelaku</th>
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
                                <td>{{$customer->periode}}</td>
                                <td>{{$customer->list_id}}</td>
                                <td>{{$customer->kode_watchlist}}</td>
                                <td>{{$customer->jenis_pelaku}}</td>
								<td>{{$customer->name}}</td>
								<td>{{$customer->id_num}}</td>
								<td>{{$customer->birthplace}}</td>
                                <td>{{$customer->birthdate}}</td>
								<form action="{{ route('korporasi.xml', $customer->id) }}" method="POST">
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
								<td colspan="12" class="text-center">Data Tidak Tersedia</td>
							@endforelse
						</tbody>
					</table>
					<div class="float-right">
						{{$customers->links()}}
					</div>
				</div>
			</div>
		</div>



		<div class="modal fade" id="downloadXML" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form method="post" action="{{ route('customer.xmlall', $cust = 'C') }}" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Save All XML</h5>
						</div>
						<div class="modal-body">

							{{ csrf_field() }}
							<input type="hidden" name="cust_type" value="C">
                            <input type="hidden" name="filterktp" id="fktpsendw">
                            <input type="hidden" name="filterbirthdate" id="fbdsendw">
                            <input type="hidden" name="filterbirthplace" id="fbpsendw">
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

        <div class="modal fade" onfocus="test2()" id="downloadXMLwatchlist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form method="post" action="{{ route('watchlist.customer.xmlall', $cust = 'C') }}" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Save All XML</h5>
						</div>
						<div class="modal-body">

							{{ csrf_field() }}
							<input type="hidden" name="cust_type" value="C">
                            <input type="hidden" name="filterktp" id="fktpsendw">
                            <input type="hidden" name="filterbirthdate" id="fbdsendw">
                            <input type="hidden" name="filterbirthplace" id="fbpsendw">
                            <p>Save All XML Watchlist?</p>
							{{-- <div class="form-group">
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
							</div> --}}

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
