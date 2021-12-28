<!-- balance area -->
<div class="row mb-3">
    <div class="d-flex justify-content-end">
        <h5>
            <form method="POST" id="reload-balance-form" action="{{ route('member.reloadbalance') }}">
                @csrf
                {{ __('Balance') }} : <span class="text-success">{{ balance_info(true) }}</span>
                <button type="submit" class="btn btn-outline-light text-black-50" title="Refresh" 
                    onclick="if (confirm('Segarkan informasi saldo?')) {$('#reload-balance-form').submit();} else {return false;}">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </form>
        </h5>
    </div>
</div>
<!-- endof balance area -->