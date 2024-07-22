<!DOCTYPE html>
<html lang="en">

<head>
	@include('manager.css')
</head>

<body>
	<div class="wrapper">
		@include('manager.sidebar')

		<div class="main">
			@include('manager.navbar')

			<main class="content" style="padding: 10px">
				<div class="container-fluid p-0">
               <div class="container-fluid py-4"> 
                  <div class="container">
                    <div style="border-radius:30px" class="card shadow-lg p-3 rounded-pill-4">
                      <div class="container-fluid">
                        <div class="card-body">
                        <div class="container">
                          <div class="py-3">
                            <p class="title-page">Data Subkontraktor</p>
                        </div>
                            <p></p>
                        </div>
                            <div class="table-responsive">
                              <table class="table" style="text-align: center">
                                <thead>
                                  <tr>
                                    <th>Nama</th>
                                    <th>Kontak</th>
                                    <th>Pekerja</th>
                                    <th>Aksi</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach ($subkontraktors as $subkontraktor)
                                  <tr>
                                    <td>{{$subkontraktor->subkontraktor_name}}</td>
                                    <td>{{$subkontraktor->contact}}</td>
                                    <td>{{$subkontraktor->employee}}</td>
                                    <td>  
                                        <a class="btn-edit" href="{{ url('showDetail', $subkontraktor->id) }}" title="Detail"><i class="fa-regular fa-eye" data-feather="info"></i></a>
                                    </td>
                                  </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </div>
                            <!-- Pagination Links -->
                            <div class="pagination">
                                @if ($subkontraktors->onFirstPage())
                                    <span>&laquo;</span>
                                @else
                                    <a href="{{ $subkontraktors->previousPageUrl() }}" rel="prev">&laquo;</a>
                                @endif
                        
                                @php
                                    $start = max(1, $subkontraktors->currentPage() - 2);
                                    $end = min($subkontraktors->lastPage(), $subkontraktors->currentPage() + 2);
                                @endphp
                        
                                @if ($start > 1)
                                    <a href="{{ $subkontraktors->url(1) }}">1</a>
                                    @if ($start > 2)
                                        <span>...</span>
                                    @endif
                                @endif
                        
                                @for ($page = $start; $page <= $end; $page++)
                                    @if ($page == $subkontraktors->currentPage())
                                        <a class="active">{{ $page }}</a>
                                    @else
                                        <a href="{{ $subkontraktors->url($page) }}">{{ $page }}</a>
                                    @endif
                                @endfor
                        
                                @if ($end < $subkontraktors->lastPage())
                                    @if ($end < $subkontraktors->lastPage() - 1)
                                        <span>...</span>
                                    @endif
                                    <a href="{{ $subkontraktors->url($subkontraktors->lastPage()) }}">{{ $subkontraktors->lastPage() }}</a>
                                @endif
                        
                                @if ($subkontraktors->hasMorePages())
                                    <a href="{{ $subkontraktors->nextPageUrl() }}" rel="next">&raquo;</a>
                                @else
                                    <span>&raquo;</span>
                                @endif
                            </div>
                          </div>
                          </div>
                          </div>
                        </div>
                      </div>
                </div>
					
			</main>

			<footer class="footer">
				@include('manager.footer')
			</footer>
		</div>
	</div>

	@include('manager.js')

</body>

</html>