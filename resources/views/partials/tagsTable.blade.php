@if (!$tags->isEmpty())
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-hover mx-auto" style="max-width: 650px;">
                <thead>
                    <tr>
                        <th class="text-left align-middle">Tag</th>
                        <th class="text-right">
                            <a id="tagBtn" class="btn btn-primary float-right align-middle"><i class="fa fa-plus text-white"></i>
                        </a>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tags as $tag)
                        <tr>
                            <td class="align-middle">
                                #{{ $tag->title }}
                            </td>
                            <td class="text-right align-middle">
                                <form method="POST" action="{{ route('tag.delete', $tag->id) }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-center">
                            {{ $tags->links('partials.pagination') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="card m-3">
        <div class="card-body text-center">
            <h4 class="card-title">No tags</h4>
            <p class="card-text">There are currently no tags</p>
        </div>
    </div>
@endif