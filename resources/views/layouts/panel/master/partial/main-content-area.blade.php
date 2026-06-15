<main id="mainContent" class="col-md-9 col-lg-10 p-0 h-100 overflow-hidden d-flex flex-column bg-body transition-all position-relative">
    <!-- Loading Progress Bar -->
    <div id="iframeLoadingBar" class="progress position-absolute top-0 start-0 w-100 rounded-0" style="height: 3px; z-index: 1050; display: none;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <iframe name="main-frame" id="main-frame" src="{{ url('dashboard') }}" title="Main Content" class="w-100 h-100 border-0 flex-grow-1"></iframe>
</main>