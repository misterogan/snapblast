<template>
    <div class="container">
        <div class="d-flex align-center justify-between p-6 relative">
            <div>
                <h1 class="page-title">
                    Dashboard
                </h1>
            </div>
            <div class="date-range">
                <input type="text" name="daterange" value="01/01/2018 - 01/15/2018" />
            </div>
        </div>
        <ul class="dashboard-summary pl-6 pr-6">
            <li>
                <div class="title">
                    Total Leads
                </div>
                <div class="value">
                    1.200
                </div>
            </li>
            <li>
                <div class="title">
                    Followed Up
                </div>
                <div class="value">
                    1.000
                    <small>80%</small>
                </div>
            </li>
            <li>
                <div class="title">
                    Pending
                </div>
                <div class="value">
                    200
                    <small>20%</small>
                </div>
            </li>
            <li>
                <div class="title">
                    Campaign
                </div>
                <div class="value">
                    1
                </div>
            </li>
        </ul>
        <div class="d-flex dashboard-data p-6">
            <div class="data shadow">
                <div class="graph p-2">
                    <img src="/images/chart.png" alt="" width="100%">
                </div>
                <div class="last-data p-3">
                    <h2 class="title">Last Data :</h2>
                    <ul class="last-data-item">
                        <li>
                            <div class="col left">
                                <div class="sub-title">Full Name</div>
                                <div class="name mb-1">Agust Erwinson</div>
                                <div class="d-flex">
                                    <div class="mr-7">
                                        <div>
                                            <div class="sub-title">Email</div>
                                            <div class="email mb-1">agust@cashtree.id</div>
                                        </div>
                                        <div>
                                            <div class="sub-title">Phone</div>
                                            <div class="email mb-1">081314130764</div>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <div class="sub-title">Source</div>
                                            <div class="source mb-1">Google</div>
                                        </div>
                                        <div>
                                            <div class="sub-title">Campaign</div>
                                            <div class="campaign">Home Experience #1</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col right">
                                <div class="sub-title mb-0">Status</div>
                                <div class="status mb-2">
                                    <span class="badge warning">
                                        Pending
                                    </span>
                                </div>
                                <div class="sub-title">PIC</div>
                                <div class="pic mb-1 fs">Agust</div>
                                <div class="sub-title">Date</div>
                                <div class="date fs">Selasa, 10-10-2022 09:08</div>
                            </div>
                        </li>
                        <li>
                            <div class="col left">
                                <div class="sub-title">Full Name</div>
                                <div class="name mb-1">Agust Erwinson</div>
                                <div class="d-flex">
                                    <div class="mr-7">
                                        <div>
                                            <div class="sub-title">Email</div>
                                            <div class="email mb-1">agust@cashtree.id</div>
                                        </div>
                                        <div>
                                            <div class="sub-title">Phone</div>
                                            <div class="email mb-1">081314130764</div>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <div class="sub-title">Source</div>
                                            <div class="source mb-1">Google</div>
                                        </div>
                                        <div>
                                            <div class="sub-title">Campaign</div>
                                            <div class="campaign">Home Experience #1</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col right">
                                <div class="sub-title mb-0">Status</div>
                                <div class="status mb-2">
                                    <span class="badge primary">
                                        Followed Up
                                    </span>
                                </div>
                                <div class="sub-title">PIC</div>
                                <div class="pic mb-1 fs">Agust</div>
                                <div class="sub-title">Date</div>
                                <div class="date fs">Selasa, 10-10-2022 09:08</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="log shadow">
                <div class="title">
                    LEADS IN LAST 1 HOUR
                </div>
                <div class="value mb-2">
                    18
                </div>
                <div class="title">
                    FOLLOWED UP IN LAST 1 HOUR
                </div>
                <div class="value mb-2">
                    180
                </div>
                <div class="title d-flex justify-between">
                    <span>TOP USER</span>
                    <span>LEADS</span>
                </div>
                <table>
                    <tr>
                        <td>Agust</td>
                        <td class="text-right">130</td>
                    </tr>
                    <tr>
                        <td>Indra</td>
                        <td class="text-right">30</td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Dashboard",
    mounted() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'left'
        }, function (start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    },
    computed:{

    }
}
</script>

<style scoped>

</style>
