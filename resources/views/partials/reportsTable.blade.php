@if (!$reports->isEmpty())
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-hover mx-auto" style="max-width: 1000px;">
                <thead>
                    <tr>
                        <th style="width: 33%;">Title</th>
                        <th class="text-center" style="width: 33%;">Author</th>
                        <th class="text-right" style="width: 33%;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>
                                <span class="badge {{ $report->closed ? 'badge-danger' : 'badge-success' }}">{{ $report->closed ? 'Closed' : 'Open' }}</span>
                                <a class="text-decoration-none text-truncate" style="text-decoration: none; color: inherit;" href="{{ route('report.details', $report->id) }}">{{ $report->title }}</a>
                            </td>
                            <td class="text-center">
                                @if($report->user)
                                    <a class="text-decoration-none" style="text-decoration: none; color: inherit;" href="{{ route('user.profile', $report->user->id) }}">
                                        {{ $report->user->username }}
                                    </a>
                                @else
                                    <strong style="color: red; text-decoration: line-through;">USER DELETED</strong> 
                                @endif
                            </td>
                            <td class="text-right">{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-center">
                            {{ $reports->links('partials.pagination') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="card m-3">
        <div class="card-body text-center">
            <h4 class="card-title">No reports</h4>
            <p class="card-text">There are currently no reports</p>
        </div>
    </div>
@endif