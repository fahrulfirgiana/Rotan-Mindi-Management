<div class="py-3">
	<h1 class="title-pag">Statistik Produksi</h1>
</div>

<div class="row">
	<div class="col-xl-6 col-xxl-5 d-flex">
		 <div class="w-100">
			  <div class="row">
					<div class="col-sm-6">
						 <div class="card dibi">
							  <div class="card-body">
									<div class="row">
										 <div class="mt-0 col">
											  <h5 class="card-title">Total Pesanan</h5>
										 </div>
										 <div class="col-auto">
											  <span class="align-middle material-symbols-outlined icon-size">
													shopping_bag
											  </span>
										 </div>
									</div>
									<div class="price">
										 <h1 class="mt-1 mb-3"></h1>
									</div>
									<div class="price">
										 <h1 class="mt-1 mb-3">{{ $totalOrders }}</h1>
									</div>
							  </div>
						 </div>
						 <div class="card dibi">
							  <div class="card-body">
									<div class="row">
										 <div class="mt-0 col">
											  <h5 class="card-title">Total Produk</h5>
										 </div>
										 <div class="col-auto">
											  <span class="align-middle material-symbols-outlined icon-size">
													shopping_bag
											  </span>
										 </div>
									</div>
									<div class="price">
										 <h1 class="mt-1 mb-3"></h1>
									</div>
									<div class="price">
										 <h1 class="mt-1 mb-3">{{ $totalProducts }}</h1>
									</div>
							  </div>
						 </div>
					</div>
					<div class="col-sm-6">
						 <div class="card dibi">
							  <div class="card-body">
									<div class="row">
										 <div class="mt-0 col">
											  <h5 class="card-title">Produksi Selesai</h5>
										 </div>
										 <div class="col-auto">
											  <span class="align-middle material-symbols-outlined icon-size">
													receipt
											  </span>
										 </div>
									</div>
									<div class="price">
										 <h1 class="mt-1 mb-3"></h1>
									</div>
									<div class="price">
										 <h1 class="mt-1 mb-3">{{ $totalProduction }}</h1>
									</div>
							  </div>
						 </div>
						 <div class="card dibi">
							  <div class="card-body">
									<div class="row">
										 <div class="mt-0 col">
											  <h5 class="card-title">Subkontraktor</h5>
										 </div>
										 <div class="col-auto">
											  <span class="align-middle material-symbols-outlined icon-size">
													person
											  </span>
										 </div>
									</div>
									<div class="mb-0 price">
										 <h1 class="mt-1 mb-3"></h1>
									</div>
									<div class="mb-0 price">
										 <h1 class="mt-1 mb-3">{{ $totalSubcontractors }}</h1>
									</div>
							  </div>
						 </div>
					</div>
			  </div>
		 </div>
	</div>

	<div class="col-xl-6 col-xxl-7">
		 <div class="card flex-fill w-100 dibi">
			  <div class="card-header">
					<h5 class="mb-0 card-title">Produksi Terbanyak</h5>
			  </div>
			  <div class="card-body chart chart-sm" style="display: flex;">
					<div class="chart-container">
						 <canvas id="bestSellingProductsChart"></canvas>
					</div>
					<div class="legend-container">
						 <div class="legend-item">
							  <div class="legend-color" style="background-color: #2d3436;"></div>
							  <span id="legend-item-0"></span>
						 </div>
						 <div class="legend-item">
							  <div class="legend-color" style="background-color: #636e72;"></div>
							  <span id="legend-item-1"></span>
						 </div>
						 <div class="legend-item">
							  <div class="legend-color" style="background-color: #b2bec3;"></div>
							  <span id="legend-item-2"></span>
						 </div>
					</div>
			  </div>
		 </div>
	</div>
</div>

<div class="row">
	<div class="col-12 d-flex">
		 <div class="w-100">
			  <div class="row">
					<div class="col-sm-12">
						 <div class="card dibi">
							  <div class="card-body">
									<h5 class="card-title">Progress Produksi : {{ $totalInProgress }}</h5>
									<div class="table-responsive">
										 <table class="table table-striped">
											  <thead>
													<tr>
														 <th>Nama Produk</th>
														 <th>Sedang Produksi</th>
														 <th>Produksi Selesai</th>
													</tr>
											  </thead>
											  <tbody>
													@foreach ($productions as $production)
													<tr>
														 <td>{{ $production->product_name }}</td>
														 <td>{{ $production->total_in_progress }}</td>
														 <td>{{ $production->total_production }}</td>
													</tr>
													@endforeach
											  </tbody>
										 </table>
									</div>
							  </div>
						 </div>
					</div>
			  </div>
		 </div>
	</div>
</div>

<!-- Place for your other contents such as cards, charts, etc. -->
<div class="row">
  <div class="col-12 col-lg-6 col-xxl-6 d-flex">
	  <div class="card flex-fill w-100">
		  <div class="card-header">
			  <h5 class="mb-0 card-title">Penjualan Bulanan</h5>
		  </div>
		  <div class="card-body d-flex w-100">
			  <div class="align-self-center chart chart-lg">
				  <canvas id="chartjs-dashboard-bar"></canvas>
			  </div>
		  </div>
	  </div>
  </div>
  <div class="col-12 col-lg-6 col-xxl-6 d-flex calendar-container">
	  <div class="card flex-fill w-100">
		  <div class="card-body d-flex">
			  <div class="align-self-center w-100">
				  <div class="chart">
					  <div id="datetime-dashboard"></div>
					  <!-- Modal placed inside the calendar container -->
					  <div class="calendar-modal" id="customOrderModal">
						  <div class="modal-content">
							  <div class="modal-header">
								  <h5 class="modal-title" id="orderDetailsModalLabel">Detail Pesanan</h5>
								  <button type="button" class="bg-white btn-close" id="closeCustomOrderModal" aria-label="Close"></button>
							  </div>
							  <div class="modal-body">
								  <div id="orderDetailsContent">
									  @foreach ($orders as $order)
									  <div class="calendar-date" data-date="{{ $order->deadline }}">
									  <div class="order-details" data-id="{{ $order->id }}">
									  <p>Nama Produk: {{ $order->product_name }}</p>
									  <p>Sedang Diproduksi: {{ $order->sedang_progress }}</p>
									  <p>Produksi Selesai: {{ $order->progress }}</p>
								  </div>
								  </div>
								  @endforeach
								  </div>
							  </div>
						  </div>
					  </div>
				  </div>
			  </div>
		  </div>
	  </div>
  </div>
</div>
</div>