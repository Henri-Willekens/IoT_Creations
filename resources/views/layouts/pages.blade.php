<script type="text/javascript">

    function replaceQueryStringVal(values) {
        let url = window.location.origin + window.location.pathname
        let currentSearch = window.location.search.substring(1)

        let newSearch = "?"
        for (let item of currentSearch.split("&")) {
            let [ key, value] = item.split("=")
            if (!values.includes(key)) {
                newSearch += item + "&"
            }
        }
        return url + newSearch
    }

    function inItemPerPageChange(event) {
        let value = event.target.value
        let url = replaceQueryStringVal(["limit", "page"])
        window.location = url + "limit=" + value
    }
    function onSortChange(event) {
        let [ orderBy, order ] = event.target.value.split("-")
        let url = replaceQueryStringVal(["orderBy", "order"])
        window.location = url + "orderBy=" + orderBy + "&order=" + order
    }
    function onPageChange(page) {
        let url = replaceQueryStringVal(["page"])
        window.location = url + "page=" + page
    }
</script>
<div style="width: 100%; display:block; margin-bottom: 10px; padding-bottom: 4px; border-bottom: 1px solid black;" id="page-containers">
    <div class="mb-2">
        <div style="display: inline-block; padding-right: 8px;">
            Sort by:
            <select onchange="onSortChange(event)">
                @foreach (array("id", "price", "name") as $ordBy)
                    @foreach (array("asc", "desc") as $ord)
                        <option value="{{ $ordBy }}-{{ $ord }}" {{ $ordBy == $orderBy && $ord == $order ? "selected" : "" }}>
                            {{
                                [
                                    "id.asc" => "Oldest",
                                    "id.desc" => "Newest",
                                    "price.asc" => "Lowest price",
                                    "price.desc" => "Highest price",
                                    "name.asc" => "Name (A-Z)",
                                    "name.desc" => "Name (Z-A)",
                                ][$ordBy.".".$ord];
                            }}
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="vr bg-black mx-2 d-none d-lg-inline-block"></div>
        <div style="display: inline-block; padding-right: 8px;">
            Items per page:
            <select onchange="inItemPerPageChange(event)">
                @foreach (array(5, 8, 10, 20, 50) as $value)
                    <option value="{{ $value }}" {{ $itemPerPage == $value ? "selected" : "" }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="vr bg-black mx-2 d-none d-lg-inline-block"></div>
        <div style="display:inline-block;">{{ $totalItemCount }} results</div>
    </div>
    <div style="display: inline-block;margin-left: 4px;">
        @for ($i = 1; $i <= $page_count; $i++)
            <a onClick="onPageChange({{ $i }})" class="btn {{ $i == $current_page ? "bg-primary"  : "bg-secondary" }} text-white mb-1" style="padding-left: 0; padding-right: 0; width: 40px; font-size: 14px;">{{ $i }}</a>
        @endFor
    </div>
</div>
