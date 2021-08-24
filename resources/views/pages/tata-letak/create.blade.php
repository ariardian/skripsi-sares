@extends('layouts.app')
@section('content')
    @csrf
    @method('POST')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 col-sm-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <h4>Proses Tata Letak</h4>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="modal-body" id="mediumBody">
                            <form id="roleForm">
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="support">Minimum Support (%)</label>
                                        <input id="support" type="text" name="support" placeholder="input support..."
                                            class="form-control" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="confidence">Minimum Confidence (%)</label>
                                        <input id="confidence" type="text" name="confidence"
                                            placeholder="Input confidence..." class="form-control" required>
                                    </div>
                                </div>
                                <button id="buttonProc" data-action="{{ route('tata-letak.store') }}"
                                    class="btn btn-primary buttonProc mb-4">Proses Data</button>
                            </form>
                            @include('inc.component', ['as' => 'loader','type' => 'loader-modal', 'count' => 2])
                            <div id="summary" class="vertical-line-pill">
                                <div class="modal-footer"></div>
                                <div class="row mb-4 mt-3">
                                    <div class="col-sm-3 col-12">
                                        <div class="nav flex-column nav-pills mb-sm-0 mb-3 text-center mx-auto"
                                            id="v-line-pills-tab" role="tablist" aria-orientation="vertical"
                                            style="width: unset !important">
                                        </div>
                                    </div>

                                    <div class="col-sm-9 col-12">
                                        <div class="tab-content" id="v-line-pills-tabContent">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="areaSave" class="modal-footer">
                            <button id="btnSaveRekomendasi" data-action="{{ route('admin.tata-letak.store') }}"
                                class="btn btn-primary buttonSaveRole">Save
                                Data</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#summary').hide();
            $('#modal-loader').hide();
            let storeData = {};
            // console.log(storeData?.dataRule.length)
            const showHideButtonSave = () => {
                let buttonSave = $('#areaSave');
                if (storeData && storeData.dataRule && storeData.dataRule.length) {
                    buttonSave.show()
                } else {
                    buttonSave.hide()
                }
            }
            showHideButtonSave()

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
                // console.log(result)
                return result;
            }

            // Filter berdasarkan golden rule(treshold) minimum support
            function isNumber(val) {
                return val && val !== "" && val != null && val !== undefined && !isNaN(val) ? val : false
            }

            function filterDataTransaksi(data, total) {
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
                let totalCount = data && data.length && Number(total)
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
                    // console.log("kombinasi =", comb)
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
                    // console.log("COUNT = ", isCount)
                    return isCount
                }
                data.map(value => {
                    result.push({
                        pasanganItem: value,
                        count: setCount(value)
                    })
                })
                // console.log("result coumnt frequent ", result)
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
                    result = [...tempResult].reduce((acc, cur) => {
                        const isSame = (data, data2) => {
                            let tmp1 = Array.isArray(data) ? data : data.split("-");
                            let tmp2 = Array.isArray(data2) ? data2 : data2.split("-");
                            return [...tmp1].sort().join(",") === [...tmp2].sort().join(",")
                        }
                        const isDefine = (a, b) => {
                            let tempA = Array.isArray(a) ? a : [a];
                            let tempB = Array.isArray(b) ? b : b.split("-");

                            let dataSame = tempA.find(val => isSame(val, tempB));
                            return dataSame;
                        }
                        if (isDefine(acc, cur)) {
                            return [...acc]
                        } else {
                            return [...acc, cur]
                        }
                    }, [])
                }
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
                    return isCount
                }
                data.map(value => {
                    result.push({
                        pasanganItem: value,
                        count: setCount(value)
                    })
                })
                return result;
            }

            // set data rule association
            function setRule(data, dataTransaksi) {
                let result = [];
                let itemCombination = [];
                let tmp = data.map(res => res.pasanganItem)
                // console.log("TMP =", tmp)
                let combine = tmp.map((res, key) => {
                    let resultComb = []
                    let dataForSet = res?.split("-")
                    let comb = [...dataForSet].map((v, i) => {
                        let combItem = [...dataForSet].map((val, id) => {
                            if (id <= i) {
                                return null
                            }
                            return v + "-" + val
                        }).filter(val => val !== null)
                        // console.log("combItem", combItem)
                        return combItem
                    }).filter(v => v !== null)
                    // console.log("comb", comb)
                    return comb
                })
                if (combine.length) {
                    itemCombination = combine.map(res => res.reduce((acc, cur) => [...acc, ...cur])).reduce((acc,
                        cur) => [...acc, ...cur])
                    itemCombination = [...new Set(itemCombination)]
                    let reverseItemCombination = [...itemCombination].map(res => res.split("-").reverse().join("-"))
                    itemCombination = [...itemCombination, ...reverseItemCombination]
                }
                // console.log("combine", combine, itemCombination)

                result = itemCombination.map((res, i) => {
                    return {
                        item: res,
                        support: getSupport(res, dataTransaksi),
                        confidence: getConfidence(res, dataTransaksi)
                    }
                })
                return result;
            }

            function getSupport(data, dataTransaksi) {
                let listItem = [...dataTransaksi];
                let totalTransaksi = dataTransaksi?.length;
                let valToArray = data.split("-")
                let num1 = (valToArray?.[0] || 0);
                let num2 = (valToArray?.[1] || 0);
                const setCount = (val) => {
                    let isFind = [false, false];
                    let isCount = 0;
                    listItem.forEach(res => {
                        res.item.forEach((v, i) => {
                            // isFind[i] = res.item.includes(v)
                            // console.log("COMPARE = ",v, val)
                            isCount = Number(isCount) + ((v == val) ? 1 : 0)
                        })
                    })
                    return Number(isCount)
                }
                // console.log(num1, data)
                let dataCalc = (setCount(num1) / totalTransaksi) * 100;
                return dataCalc
            }

            function getConfidence(data, dataTransaksi) {
                let listItem = [...dataTransaksi];
                // let totalTransaksi = dataTransaksi?.length;
                let splitVar = data.split("-")
                let num1 = (splitVar?.[0] || 0);
                let num2 = (splitVar?.[1] || 0);

                const setCountCombination = (val) => {
                    let isFind = [false, false];
                    let isCount = 0;
                    let valToArray = val.split("-")
                    listItem.forEach(res => {
                        valToArray.forEach((v, i) => isFind[i] = res.item.includes(v))
                        isCount = Number(isCount) + (!isFind.includes(false) ? 1 : 0)
                    })
                    return isCount
                }
                const setCount = (val) => {
                    let isFind = [false, false];
                    let isCount = 0;
                    listItem.forEach(res => {
                        res.item.forEach((v, i) => {
                            isCount = Number(isCount) + ((v == val) ? 1 : 0)
                        })
                    })
                    return Number(isCount)
                }
                // console.log(data, num1, num2)
                let dataCalc = (setCountCombination(data) / setCount(num1)) * 100;
                return dataCalc
            }

            function setRekomendasi(data) {
                let inpSup = $("#support")?.[0].value
                let inpCon = $("#confidence")?.[0].value
                let dataFilter = data.length && data.filter(res => Number(res.support) >= Number(inpSup) && Number(
                    res.confidence) >= Number(inpCon))
                return dataFilter;
            }


            // SAVE REKOMENDAI KE DATABASE
            $(document).on('click', '#btnSaveRekomendasi', function(event) {
                event.preventDefault();
                let tmpStoreData = {
                    ...storeData
                }
                let dataUrl = $(this).attr('data-action');
                console.log(tmpStoreData.dataRule)
                let dataFrom = tmpStoreData.rekomendasi ? tmpStoreData.rekomendasi : []
                $.ajax({
                    data: JSON.stringify(dataFrom),
                    url: dataUrl,
                    type: "POST",
                    dataType: 'json',
                    contentType: 'application/json',
                    success: function(data) {
                        // console.log("Here", data)
                        if (data.code == 200) {
                            swal(
                                'Success!',
                                data.success,
                                'success'
                            )
                        }
                    },
                    error: function(data) {
                        swal(
                            'Error!',
                            (data?.success || "Gagal menyimpan data"),
                            'error'
                        )
                    }
                });

            })

            // button proses
            $(document).on('click', '#buttonProc', function(event) {
                event.preventDefault();
                let domNav = $("#v-line-pills-tab")?.[0];
                let domContent = $("#v-line-pills-tabContent")?.[0];
                let formSupport = $("#support")?.[0]
                let formConfidence = $("#confidence")?.[0]
                formConfidence.classList.remove("is-invalid")
                const checkValid = (val, label) => {
                    if (val.value != "" && isNaN(val.value)) {
                        swal('Uppsss!', `${label} hanya bisa diisi oleh angka`, 'warning');
                        val.classList.add("is-invalid");
                        return false
                    }
                    if (val.value == "") {
                        swal('Uppsss!', `Input minimum ${label} harus diisi`, 'warning')
                        val.classList.add("is-invalid")
                        return false
                    }

                    if (val.value >= 100 || val.value <= 0) {
                        swal('Uppsss!', "Range input dari 1 - 100", 'warning');
                        val.classList.add("is-invalid");
                        return false
                    }
                    return true
                }
                if (formSupport && formConfidence) {
                    formSupport.classList.remove("is-invalid")
                    formConfidence.classList.remove("is-invalid")
                    checkValid(formSupport, "Support")
                    checkValid(formConfidence, "Confidence")
                    if (!checkValid(formSupport, "Support") || !checkValid(formConfidence, "Confidence"))
                        return
                }
                domNav.innerHTML = "";
                domContent.innerHTML = "";
                $('#summary').hide();
                $('#modal-loader').show();

                let resultdataTransaction = {!! json_encode($result->toArray()) !!} || [];
                let dataTransaksi = setDataTransaksi(resultdataTransaction);
                let dataTransaksiPerItem = countDataTransaksiPerItem(dataTransaksi);
                let filterDataTransaksiPerItem = filterDataTransaksi(dataTransaksiPerItem, dataTransaksi
                    .length);
                let dataFrequent2 = setFrequent2(filterDataTransaksiPerItem);
                let countFrequent2 = setCountFrequent2(dataFrequent2, dataTransaksi);
                let filterDataFrequent2 = filterDataTransaksi(countFrequent2, dataTransaksi.length);
                let dataFrequent3 = setFrequent3(filterDataFrequent2);
                let countFrequent3 = setCountFrequent3(dataFrequent3, dataTransaksi);
                let filterDataFrequent3 = filterDataTransaksi(countFrequent3, dataTransaksi.length);
                let dataRule = setRule(filterDataFrequent3, dataTransaksi);
                let rekomendasi = setRekomendasi(dataRule);

                storeData = {
                    resultdataTransaction,
                    dataTransaksi,
                    dataTransaksiPerItem,
                    filterDataTransaksiPerItem,
                    dataFrequent2,
                    countFrequent2,
                    filterDataFrequent2,
                    dataFrequent3,
                    countFrequent3,
                    filterDataFrequent3,
                    dataRule,
                    rekomendasi,
                }
                let dataStep = [{
                        id: 1,
                        label: "Data Transaksi",
                        titte: "Data transaksi yang akan diolah ",
                        data: dataTransaksi,
                    },
                    {
                        id: 2,
                        label: "Banyaknya Transaksi",
                        titte: "Hitung banyaknya transaksi setiap item",
                        data: dataTransaksiPerItem,
                    },
                    {
                        id: 3,
                        label: "Filter Data",
                        titte: `Filter traksaksi berdasarkan golden rule dengan support minimum ${$("#support")?.[0]?.value}%`,
                        data: filterDataTransaksiPerItem,
                    },
                    {
                        id: 4,
                        label: "Data Frequent-2",
                        titte: `Buat pasangan item dari data yang sudah di filter`,
                        data: dataFrequent2,
                    },
                    {
                        id: 5,
                        label: "Banyaknya Frequent-2",
                        titte: `Hitung banyaknya pasangan item dibeli secara bersamaan`,
                        data: countFrequent2,
                    },
                    {
                        id: 6,
                        label: "Filter Frequent-2",
                        titte: `Filter frequent-2 berdasarkan golden rule dengan support ${$("#support")?.[0]?.value}%`,
                        data: filterDataFrequent2,
                    },
                    {
                        id: 7,
                        label: "Data Frequent-3",
                        titte: `Buat pasangan 3 item dari data yang sudah di filter`,
                        data: dataFrequent3,
                    },
                    {
                        id: 8,
                        label: "Banyaknya Frequent-3",
                        titte: `Hitung banyaknya pasangan item dibeli secara bersamaan`,
                        data: countFrequent3,
                    },
                    {
                        id: 9,
                        label: "Hasil Frequent-3",
                        titte: `Hasil akhir dari Frequent-3`,
                        data: filterDataFrequent3,
                    },
                    {
                        id: 10,
                        label: "Rule Association",
                        titte: `Rule - rule yang dihasilkan dari hasil frequent`,
                        data: dataRule,
                    },
                    {
                        id: 11,
                        label: "Kesimpulan",
                        titte: `Data yang memenuhi support dan confidence`,
                        data: rekomendasi,
                    },
                ]
                console.log(dataStep, dataRule)

                function renderItem(data) {
                    return `<span class="badge outline-badge-success"> ${data} </span>`
                }

                function renderDataTransaksi(data) {
                    let table = `<table class='table table-bordered'>
                                    <thead>
                                        <tr>
                                        <th scope="col">Transaksi</th>
                                        <th scope="col">Item yang dibeli</th>
                                        </tr>
                                    </thead>`
                    data.forEach((res, key) => {
                        table = table + "<tr><td>" + (key + 1) + "</td><td>" + res.item.map(item =>
                            renderItem(item)).join(" ") + "</td></tr>";
                    })
                    table = table + "</table>";
                    return table
                }

                function renderCount(data) {
                    let table = `<table class='table table-bordered'>
                                    <thead>
                                        <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Item</th>
                                        <th scope="col">Banyaknya Transaksi</th>
                                        </tr>
                                    </thead>`
                    data.forEach((res, key) => {
                        table = table + "<tr><td>" + (key + 1) + "</td><td>" + ((res.item || res
                            .pasanganItem)?.split("-")).map(item => renderItem(item))?.join(
                            "-") + "</td><td>" + res.count + "</td></tr>";
                    })
                    table = table + "</table>";
                    return table
                }

                function renderDataFrequent(data) {
                    let table = `Dari data diatas diperolehlah kombinasi item sebagai berikut:
                    <table class='table table-bordered'>
                                    <thead>
                                        <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Item</th>
                                        </tr>
                                    </thead>`
                    data.forEach((res, key) => {
                        table = table + "<tr><td>" + (key + 1) + "</td><td>" + ((res || "")?.split(
                            "-")).map(item => renderItem(item)).join("-") + "</td></tr>";
                    })
                    table = table + "</table>";
                    return table
                }

                function renderDataKesimpulan(data) {
                    let table = `Hasil rule beserta support dan confidence nya yaitu:
                    <table class='table table-bordered'>
                                    <thead>
                                        <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Rule</th>
                                        <th scope="col">Support</th>
                                        <th scope="col">Confidence</th>
                                        </tr>
                                    </thead>`
                    data.forEach((res, key) => {
                        let rule = ((res.item || "")?.split("-"));
                        let rule1 = rule[0] ? rule[0] : "data";
                        let rule2 = rule[1] ? rule[1] : "data";
                        table = table + "<tr><td>" + (key + 1) + "</td><td>" +
                            `if buy ${renderItem(rule1)} then buy ${renderItem(rule2)}` +
                            "</td><td>" + `${Number(res.support).toFixed()}%` + "</td><td>" +
                            `${Number(res.confidence).toFixed()}%` + "</td></tr>";
                    })

                    table = `${table} </table>`;
                    return table
                }


                dataStep.forEach((dom, index) => {
                    let buttonNav = `<a class="nav-link ${index === 0 ? "active" : ""} mb-3" id="v-line-pills-${dom.id}-tab" data-toggle="pill"
                                        href="#v-line-pills-${dom.id}" role="tab" aria-controls="v-line-pills-${dom.id}"
                                        aria-selected="true">${dom.label}</a>`;
                    let detail = `<div class="tab-pane fade ${index === 0 ? "show active" : ""}" id="v-line-pills-${dom.id}" role="tabpanel"
                                    aria-labelledby="v-line-pills-home-tab">
                                    <h4 class="mb-4">${dom.titte}</h4>
                                    <p class="mb-4">
                                        ${
                                            index === 0 ? renderDataTransaksi(dom.data) : [2,3,5,6,8,9].includes(dom.id) ? renderCount(dom.data) : [4,7].includes(dom.id) ? renderDataFrequent(dom.data) : [10, 11].includes(dom.id) ? renderDataKesimpulan(dom.data) : null
                                        }
                                    </p>
                                </div>`
                    domNav?.insertAdjacentHTML('beforeend', buttonNav);
                    domContent?.insertAdjacentHTML('beforeend', detail);
                })
                setTimeout((e) => {
                    showHideButtonSave()
                    $('#summary').show();
                    $('#modal-loader').hide();
                }, 2000)

                // console.log("dataTransaksi", dataStep)
                // console.log("next =", countFrequent3, filterDataFrequent3)
            })

        })
    </script>
@endsection
