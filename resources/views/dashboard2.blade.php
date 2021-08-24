@extends('layouts.app')

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-table-two">

                    <div class="widget-heading">
                        <h5 class="">Rekomendasi Tata Letak</h5>
                    </div>

                    <div class="widget-content">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="denah" style="width: 276px; border: 1px solid; padding:5px">
                                    <table>
                                        <tr>
                                            <td rowspan="2"
                                                style="border: 1px solid; width: 30px; height:40px; text-align: center">
                                                2
                                            </td>
                                            <td></td>
                                            <td class="tinggi" style="border: 1px solid; width:126px; text-align: center">3
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="jarak"></td>
                                            <td></td>
                                            <td rowspan="4" style="border: 1px solid; width:30px; text-align: center">4</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="jarak"></td>
                                            <td rowspan="3" style="height: 92px">
                                                <table style="width: max-content;">
                                                    <tr>
                                                        <td
                                                            style="border: 1px solid; height: 90px; width:30px; background:#ffc107">
                                                        </td>
                                                        <td
                                                            style="border: 1px solid; height: 90px; width:30px;background:#8dbf42">
                                                        </td>
                                                        <td style="height: 90px; width:30px"></td>
                                                        <td style="border: 1px solid; height: 90px; width:30px"></td>
                                                        <td style="border: 1px solid; height: 90px; width:30px"></td>
                                                        <td style="height: 90px; width:20px"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="jarak"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="jarak"></td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid; width: 30px; text-align: center">1</td>
                                            <td class="jarak"></td>
                                            <td colspan="3"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                Keterangan:
                                <br>
                                1 = Pintu
                                <br>
                                2 = Kasir
                                <br>
                                3 = Koleksi 1
                                <br>
                                4 = Koleksi 2
                                <br>
                                <div class="d-flex">
                                    <div style="height: 15px; width:15px; background:#ffc107"></div> = Highlight 1
                                </div>
                                <div class="d-flex">
                                    <div style="height: 15px; width:15px; background:#8dbf42"></div> = Highlight 2
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="d-flex">
                                <div id="renderKeterangan" class="col-12 mt-4">
                                    Rekomendasi Penempatan Item:
                                    <br>
                                    <span class="badge badge-info" style="background-color: #ffc107;">Highlight 1</span>
                                    <div id="render1"></div>
                                    <br>
                                    <span class="badge badge-info" style="background-color: #8dbf42;">Highlight 2</span>
                                    <div id="render2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-table-two">

                    <div class="widget-heading">
                        <h5 class="">Sares Gallery</h5>
                    </div>

                    <div class="widget-content">
                        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                                <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img class="d-block w-100" src="{{ asset('assets/img/foto-3.jpeg') }}"
                                        alt="First slide">
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="{{ asset('assets/img/foto-2.jpeg') }}"
                                        alt="Second slide">
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="{{ asset('assets/img/foto-3.jpeg') }}"
                                        alt="Third slide">
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="{{ asset('assets/img/logo.png') }}" alt="Third slide">
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
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
            try {
                let dataDom = $("#renderKeterangan")
                let result = {!! json_encode($result->toArray()) !!} || [];
                result = result && result.data && result.data.length ? result.data[0] : {}
                let tanggal = new Date(result.created_at).toLocaleDateString("en-US")
                let data = result && result.data ? JSON.parse(result.data) : [];
                let splitData = data.length ? (data.length / 2).toFixed() : data.length;
                let render1 = $("#render1")?.[0]
                let render2 = $("#render2")?.[0]

                data.length && data.map((res, id) => {
                    let items = res.item.split("-")
                    let dom = ` Ke-${id+1} = Tempatkan ${items[0]} bersebelahan dengan ${items[1]}<br>`
                    if ((id + 1) <= splitData) {
                        render1?.insertAdjacentHTML('beforeend', dom);
                    } else {
                        render2?.insertAdjacentHTML('beforeend', dom);
                    }
                })
            } catch (error) {
                console.log("ERROR NI ")
            }
        })
    </script>
@endsection
