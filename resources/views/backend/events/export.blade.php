<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.cdnfonts.com/css/expletus-sans-2" rel="stylesheet">
    <title>Export Events</title>

    <style>
        table {
            width: 95%;
            border-collapse: collapse;
            margin: 50px auto;
        }

        /* Zebra striping */
        tr:nth-of-type(odd) {
            background: #eee;
        }

        th {
            background: #3498db;
            color: white;
            font-weight: bold;
        }

        td,
        th {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 18px;
        }

        body {
            /*font-family:  sans-serif; !* استخدام الخط العربي *!*/
            font-family: 'Expletus Sans', sans-serif;
        }
    </style>

</head>

<body>

    <div style="width: 95%; margin: 0 auto;">
        <div style="width: 50%; float: left;">
            <h1>All Events</h1>
        </div>
    </div>

    <table style="position: relative; top: 50px;">
        <thead>
            <tr>
                <th style="text-align: center">#</th>
                <th style="text-align: center">Event Name</th>
                <th style="text-align: center">Company Name</th>
                <th style="text-align: center">Poster</th>
                <th style="text-align: center">Category Name</th>
                <th style="text-align: center">City Name</th>
                <th style="text-align: center">Currency</th>
                <th style="text-align: center">Status</th>
                <th style="text-align: center">Is Active</th>
                <th style="text-align: center">Is Exclusive</th>
                <th style="text-align: center">Normal Price</th>
                <th style="text-align: center">Summary</th>
                <th style="text-align: center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $event)
                <tr>
                    <td>{{ $loop->iteration }} </td>
                    <td>{{ $event->name }}</td>
                    <td>{{ $event->company?->company_name ?? $event->upplied_by }}</td>
                    <td>
                        @foreach($event->media as $media)
                            @if($media->name == 'poster')
                                <a href="{{ asset('storage/' . $media->path) }}" data-lightbox="image-1">
                                    <img src="{{ asset('storage/' . $media->path) }}" alt="" style="width: 50px; height: 50px;">
                                </a>
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $event->category?->name }}</td>
                    <td>{{ $event->city?->name }}</td>
                    <td>{{ $event->currency?->code }}</td>
                    <td>
                        @if ($event->status == 1)
                            <span class="badge bg-success">Online</span>
                        @else
                            <span class="badge bg-danger">Offline</span>
                        @endif
                    </td>
                    <td>
                        @if ($event->is_active == 1)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @if ($event->is_exclusive == 1)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-danger">No</span>
                        @endif
                    </td>
                    <!-- average price -->
                    <td>{{ $event->tickets->isNotEmpty() ? $event->tickets->first()->price : 0 }}</td>
                    <td>{{ $event->summary }}</td>
                    <td>
                        <div style="display: flex">
                            {{-- @can('view slider')--}}
                            <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-xs btn-outline-warning">
                                <i class="ti ti-eye ti-sm"></i>
                            </a>
                            {{-- @endcan--}}
                            {{-- @can('update slider')--}}
                            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-xs btn-outline-info"
                                style="margin: 0 5px">
                                <i class="ti ti-pencil ti-sm"></i>
                            </a>
                            {{-- @endcan--}}
                            {{-- @can('delete slider')--}}
                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST"
                                style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-xs btn-outline-danger" type="submit">
                                    <i class="ti ti-trash ti-sm"></i>
                                </button>
                            </form>
                            {{--@endcan--}}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>