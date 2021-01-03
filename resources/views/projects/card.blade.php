<div class="card" style="height: 200px">
    <a href="{{ $project->path() }}">
        <h3 class="text-xl py-4 -ml-5 mb-2 border-l-4 border-light-blue pl-4 ">{{ $project->title }}</h3>
    </a>
    <div class="text-gray-500">{{ str_limit($project->description, 100) }}</div>
</div>

