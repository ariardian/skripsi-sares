@extends('layouts.app')

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 col-sm-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h4>Proses Tata Letak</h4>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="roleForm">
                            <div class="modal-body" id="mediumBody">
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="support">Minimum Support (%)</label>
                                        <input id="support" type="number" name="support" placeholder="input support..."
                                            class="form-control" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="confidence">Minimum Confidence (%)</label>
                                        <input id="confidence" type="number" name="confidence"
                                            placeholder="Input confidence..." class="form-control" required>
                                    </div>
                                </div>
                                <button id="buttonProc" data-action="{{ route('tata-letak.store') }}"
                                    class="btn btn-primary buttonProc mb-4">Proses Data</button>
                            </div>
                            <div class="modal-footer">
                                <button data-action="{{ route('tata-letak.store') }}"
                                    class="btn btn-primary buttonSaveRole">Save
                                    Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            // Fungsi Tansaksi barang yang dibeli
            function setDataTransaksi(data) {
                let result = []
                data.length && data.forEach((res) => {
                    result.push({
                        idTraksaksi: res.id,
                        item: (res.item_transactions || "").split(",")
                    })
                })
                return result;
            }

            // hitung berapa banyak transaksi untuk setiap item
            function countDataTransaksiPerItem(data) {
                let result = [];
                let listItem = data && data.length && data.reduce((items, item) => {
                    return [...items, ...item.item]
                }, []);
                if (listItem) {
                    let tempListItem = [...listItem]
                    let listItemUnique = [...new Set(tempListItem)];
                    const setCount = (val) => {
                        return [...listItem].filter(res => res === val).length
                    }

                    listItemUnique.length && listItemUnique.forEach(res => {
                        result.push({
                            item: res,
                            count: setCount(res)
                        })
                    })
                }
                console.log(result)
                return result;
            }

            // Filter berdasarkan golden rule(treshold) minimum support
            function isNumber(val) {
                return val && val !== "" && val != null && val !== undefined && !isNaN(val) ? val : false
            }

            function filterDataTransaksi(data) {
                let result = []
                const isMoreThan = (value, acc, totalValue) => {
                    let toPercent = (value / totalValue) * 100;
                    return Number(toPercent) >= Number(acc)
                }
                let valueNum = $("#support")?.[0]?.value;
                let getMinimumSupport = isNumber(valueNum) ? Number(valueNum) : 0;
                // let totalCount = data && data.length && data.reduce((acc, cur) => ({
                //     count: acc.count + cur.count
                // }))?.count
                let totalCount = data && data.length
                if (data && data.length) {
                    result = data.filter(val => isMoreThan(val.count, getMinimumSupport,
                        totalCount))
                }

                return result;
            }

            // buat pasangan item menjadi kombinasi 2 item
            function setFrequent2(data) {
                let result = []
                let tmp = data.map(res => res.item)
                let combine = tmp.map((res, key) => {
                    let comb = data.map((v, i) => {
                        if (i <= key) {
                            return null
                        }
                        return res + "-" + v.item
                    }).filter(v => v !== null)
                    return comb
                    console.log("kombinasi =", comb)
                })
                if (combine.length) {
                    result = combine.reduce((acc, cur) => [...acc, ...cur])
                }
                return result;
            }

            // hitung berapa kali suatu pasangan item dibeli bersamaan
            function setCountFrequent2(data, dataTransaksi) {
                let result = [];
                let listItem = [...dataTransaksi]
                let tempListItem = [...data]
                let listItemUnique = [...new Set(tempListItem)];
                const setCount = (val) => {
                    let isFind = [false, false];
                    let isCount = 0;
                    let valToArray = val.split("-")
                    listItem.forEach(res => {
                        valToArray.forEach((v, i) => isFind[i] = res.item.includes(v))
                        isCount = Number(isCount) + (!isFind.includes(false) ? 1 : 0)
                    })
                    console.log("COUNT = ", isCount)
                    return isCount
                }
                data.map(value => {
                    result.push({
                        pasanganItem: value,
                        count: setCount(value)
                    })
                })
                console.log("result coumnt frequent ", result)
                return result;
            }

            // buat pasangan item menjadi kombinasi 2 item
            function setFrequent3(data) {
                let result = []
                let tmp = data.map(res => res.pasanganItem)

                let combine = tmp.map((res, key) => {
                    let comb = data.map((v, i) => {
                        if (i <= key) {
                            return null
                        }
                        let vToArray = v.pasanganItem.split("-");
                        let resToArray = res.split("-")
                        let valueNew = [...resToArray]
                        resToArray.forEach(item => {
                            if (vToArray.includes(item)) {
                                valueNew = [...new Set([...resToArray, ...vToArray])]
                            }
                        })
                        if (valueNew && valueNew.length < 3) {
                            return null
                        }
                        return valueNew.join("-")
                    }).filter(v => v !== null)
                    return comb
                })
                if (combine.length) {
                    let tempResult = combine.reduce((acc, cur) => [...acc, ...cur])
                    // console.log("tempResult", tempResult)
                    // let isFilter = [];
                    // let filterdata = [...tempResult].map((res, key) => {
                    //     const isSame = (data, data2) => {
                    //         return data.sort().join(",") === data2.sort().join(",")
                    //     }
                    //     let resToArray = res.split("-")
                    //     let filterSame = [...tempResult].map((arr, index) => {
                    //         if (index <= key) return null;
                    //         let arrToArray = arr.split("-")
                    //         console.log("filter Same = ", index, resToArray, arrToArray)
                    //         if (isSame(resToArray, arrToArray)) {
                    //             isFilter.push(arrToArray)
                    //             return arrToArray.join("-")
                    //         }
                    //         return null
                    //     }).filter(v => v !== null)
                    //     return filterSame;
                    // })
                    // // isFilter = isFilter.reduce((acc, cur) => [...acc, ...cur])
                    // result = tempResult.filter(res => {
                    //     const isSame = (data, data2) => {
                    //         return data.sort().join(",") === data2.sort().join(",")
                    //     }
                    //     let resToArray = res.split(",")
                    //     isFilter.forEach(val => {
                    //         if (isSame(resToArray, val)) {
                    //             isFilter.push(arrToArray)
                    //             return arrToArray.join("-")
                    //         }
                    //     })
                    // })
                    // console.log("FILTER ?? ", filterdata, tempResult, isFilter)

                    // let filterdata = tempResult.map((res, key) => {
                    //     const isSame = (data, data2) => {
                    //         return data.sort().join(",") === data2.sort().join(",")
                    //     }
                    //     // let isSame = [false, false, false];
                    //     let resToArray = res.split("-")
                    //     let NewVal = [...res]
                    //     let filterSame = [...resToArray].map((arr, index) => {
                    //         if (index <= key) return null;
                    //         console.log("filter Same = ",arr)
                    //         if (isSame(resToArray, arr)) {
                    //             return arr.join("-")
                    //         }
                    //     }).filter(v => v !== null)
                    //     console.log("filter Same = ",filterSame)
                    //     return filterSame
                    // }).filter(v => v !== null)
                    // console.log("filter Data = ",filterdata)
                    // let newFilter = tempResult.filter(res => res)
                }
                console.log("data 3 = ", combine)
                return result;
            }

            // hitung banyaknya transaksi 3 pasang item
            function setCountFrequent3(data, dataTransaksi) {
                let result = [];
                let listItem = [...dataTransaksi]
                let tempListItem = [...data]
                let listItemUnique = [...new Set(tempListItem)];
                const setCount = (val) => {
                    let isFind = [false, false, false];
                    let isCount = 0;
                    let valToArray = val.split("-")
                    listItem.forEach(res => {
                        valToArray.forEach((v, i) => isFind[i] = res.item.includes(v))
                        isCount = Number(isCount) + (!isFind.includes(false) ? 1 : 0)
                    })
                    console.log("COUNT Freq 3= ", isCount)
                    return isCount
                }
                data.map(value => {
                    result.push({
                        pasanganItem: value,
                        count: setCount(value)
                    })
                })
                console.log("result coumnt frequent ", result)
                return result;
            }

            $(document).on('click', '#buttonProc', function(event) {
                event.preventDefault();
                let resultdataTransaction = {!! json_encode($result->toArray()) !!} || [];
                let dataTransaksi = setDataTransaksi(resultdataTransaction);
                let dataTransaksiPerItem = countDataTransaksiPerItem(dataTransaksi);
                let filterDataTransaksiPerItem = filterDataTransaksi(dataTransaksiPerItem);
                let dataFrequent2 = setFrequent2(filterDataTransaksiPerItem);
                let countFrequent2 = setCountFrequent2(dataFrequent2, dataTransaksi);
                let filterDataFrequent2 = filterDataTransaksi(countFrequent2);
                let dataFrequent3 = setFrequent3(filterDataFrequent2);
                let countFrequent3 = setCountFrequent3(dataFrequent3, dataTransaksi);
                let filterDataFrequent3 = filterDataTransaksi(dataFrequent3);
                let dataStep = {
                    dataTransaksi,
                    dataTransaksiPerItem,
                    filterDataTransaksiPerItem,
                    dataFrequent2,
                    countFrequent2,
                    filterDataFrequent2,
                    dataFrequent3,
                    countFrequent3,
                    filterDataFrequent3
                }
                console.log("dataTransaksi", dataStep)
            })

        })
    </script>
@endsection
