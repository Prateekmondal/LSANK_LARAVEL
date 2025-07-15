<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        {{ $title ?? 'JCR' }}
    </title>
    <link rel="icon" href="/static/favicon.ico">

    <style>
        @page {
            size: a4 landscape;
            margin: 0.5cm;
        }

        @page contingents {
            size: a4 landscape;
            margin: 2cm;
        }

        table,
        th,
        td {
            border-collapse: collapse;
        }

        th,
        tr,
        td,
        tbody,
        thead {
            padding-top: 4px;
            padding-left: 4px;
            padding-right: 4px;
        }

        td,
        p {
            font-family: Verdana, Geneva, sans-serif;
            font-size: 8px;
            word-wrap: break-word;
        }

        [colspan] {
            width: 4.5rem;
        }

        [rowspan] {
            height: 2.12rem;
        }
    </style>
</head>

<body>
    <table style="width: 100%; border:0; cellspacing:0; cellpadding:0;">
        <tr>
            <td colspan="1" align="center" style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">
                <img src="/static/images/ongc.png" width="80" />
            </td>
            <td colspan="2" align="left" style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:20px;">
                Well Logging Services<br>ONGC, Ankleshwar</td>
            <td colspan="2" align="center" style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:25px;">
                JOB COMPLETION REPORT</td>
            <td colspan="3" style="min-width: 9rem;">&nbsp;</td>
        </tr>
    </table>
    <table style="width: 100%; border:0; cellspacing:0; cellpadding:0;">
        <tr>
            <td colspan="2" align="right">Indent No.:</td>
            <td colspan="1">
                {{ $jcrs['indentNo'] }}
            </td>
            <td colspan="1">&nbsp;</td>
            <td colspan="1" align="right">Job No.:</td>
            <td colspan="1">
                {{ $jcrs['jobNo'] }}
            </td>
            <td colspan="3">&nbsp;</td>
            <td colspan="3">&nbsp;</td>
            <td colspan="5">
                <table>
                    <tr>
                        <td colspan="3"><span>Job Status:</span><span>
                                {{ $jcrs['jobStatus'] }}
                            </span></td>
                    </tr>
                    <tr>
                        <td colspan="3"><span>Logging Type:</span><span>
                                {{ $jcrs['loggingType'] }}
                            </span></td>
                    </tr>
                </table>
            </td>
            <td colspan="3">&nbsp;</td>
        </tr>
    </table>


    <table>
        <tbody>
            <tr>
                <td colspan="2" align="right" style="border: 1px solid #333;">W/O Date:</td>
                <td colspan="1" align="left" style="border: 1px solid #333;">
                    {{ date("d-m-Y", strtotime($jcrs['workOrderDate'])) }}
                </td>
                <td colspan="1" align="right" style="border: 1px solid #333;">Well No./Code:</td>
                <td colspan="2" align="left" style="border: 1px solid #333;">
                    {{ $jcrs['wellNo'] }}
                </td>
                <td colspan="1" align="right" style="border: 1px solid #333;">Rig No.:</td>
                <td colspan="1" align="left" style="border: 1px solid #333;">
                    {{ $jcrs['rigNo'] }}
                </td>
                <td colspan="1" align="right" style="border: 1px solid #333;">Unit No.:</td>
                <td colspan="2" align="left" style="border: 1px solid #333;">
                    {{ $jcrs['unitNo'] }}
                </td>
                <td colspan="2" align="right" style="border: 1px solid #333;">Mast/Van No.:</td>
                <td colspan="3" align="left" style="border: 1px solid #333;">
                    {{ $jcrs['mastVanNo'] }}
                </td>
                <td colspan="2" align="right" style="border: 1px solid #333;">Well Type:</td>
                <td colspan="1" align="left" style="border: 1px solid #333;">
                    {{ $jcrs['wellType'] }}
                </td>
                <td colspan="3" rowspan="2" align="center" style="background-color: #333; color: white; border-top: 1px solid #333; border-right: 1px solid #333;">SIDE
                    WALL CORE (SWC)
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right" style="border: 1px solid #333;">Date of Job:</td>
                <td colspan="1" align="left" style="border: 1px solid #333;">
                    {{ date("d-m-Y", strtotime($jcrs['jobDate'])) }}
                </td>
                <td colspan="1" align="right" style="border: 1px solid #333;">Field/Area:</td>
                <td colspan="2" align="left" style="border: 1px solid #333;">
                    {{ $jcrs['fieldName'] }}
                </td>
                <td colspan="1" align="center" style="border: 1px solid #333;">KB:
                    {{ $jcrs['kb'] ? $jcrs['kb'].'m' : '---' }}
                </td>
                <td colspan="1" align="center" style="border: 1px solid #333;">GL:
                    {{ $jcrs['gl'] ? $jcrs['gl'].' m' : '---' }}
                </td>
                <td colspan="1" align="center" style="border: 1px solid #333;">
                    {{ $jcrs['logType'] }}
                </td>
                <td colspan="2" align="center" style="border: 1px solid #333;">Well Owner:
                    {{ $jcrs['wellOwner'] }}
                </td>
                <td colspan="2" align="right" style="border: 1px solid #333;">LV No.:</td>
                <td colspan="3" align="left" style="border: 1px solid #333;">
                    {{ $jcrs['lvNo'] }}
                </td>
                <td colspan="3" align="center" style="border: 1px solid #333;">Rig Type:
                    {{ $jcrs['rigType'] }}
                </td>
            </tr>
            <tr rowspan="14">
                <td colspan="4" valign="top" style="border: 1px solid #333; cellspacing:0; cellpadding:0; padding: 0;">
                    <table style="width: 100%; border:0; cellspacing:0; cellpadding:0;">
                        <thead>
                            <th colspan="6" align="center" valign="top" style="border: 1px solid #333; background-color: #333; color: white; font-weight:600;">
                                TIME INFORMATION</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Assembled:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcrs['assembled_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcrs['assembled_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Departure office:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcrs['depOffice_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcrs['depOffice_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Arrival Site:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcrs['arrivalSite_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcrs['arrivalSite_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Indented:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcrs['indented_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcrs['indented_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Well Readiness:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcrs['wellReadiness_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcrs['wellReadiness_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Well Taken:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcrs['wellTaken_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcrs['wellTaken_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Rig Up:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcrs['rigUP_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcrs['rigUP_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Well Hand Over:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcrs['wellHandOver_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcrs['wellHandOver_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Departure Site:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcrs['depSite_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcrs['depSite_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Arrival Office:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcrs['arrivalOffice_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcrs['arrivalOffice_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Preparation Time:</td>
                                <td colspan="4" style="border: 1px solid #333;">
                                    {{ $jcrs['preparationTime'] ? $jcrs['preparationTime'].' HRS.' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Post Proce. Time:</td>
                                <td colspan="4" style="border: 1px solid #333;">
                                    {{ $jcrs['postProceTime'] ? $jcrs['postProceTime'].' HRS.' : '---' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td colspan="4" valign="top" style="border: 1px solid #333; cellspacing:0; cellpadding:0; padding: 0;">
                    <table style="width: 100%; border:0; cellspacing:0; cellpadding:0;">
                        <thead>
                            <th colspan="4" align="center" style="border: 1px solid #333;background-color: #333; color: white; font-weight:600;">
                                WELL INFORMATION
                            </th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Depth Driller:</td>
                                <td colspan="1" style="border: 1px solid #333;">1234 m
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Depth Logger:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcrs['depthLogger'] ? $jcrs['depthLogger'].' m' : '---' }}
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Casing Size(inch):</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcrs['casingSize'] ? $jcrs['casingSize'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">C/Shoe Driller:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcrs['casingShoeDriller'] ? $jcrs['casingShoeDriller'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">C/Shoe Logger:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcrs['casingShoeLogger'] ? $jcrs['casingShoeLogger'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Float Collar:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcrs['floatCollar'] ? $jcrs['floatCollar'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Bit Size(inch):</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcrs['bitSize'] ? $jcrs['bitSize'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Tubing Size(inch):</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcrs['tubingSize'] ? $jcrs['tubingSize'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">T/Shoe/Packer:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcrs['t_shoe_packer'] ? $jcrs['t_shoe_packer'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">S/Nipple Top Exp.:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcrs['s_nippletopexp'] ? $jcrs['s_nippletopexp'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">THP</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcrs['THP'] ? $jcrs['THP'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Max Dev at:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcrs['maxDevAt'] ? $jcrs['maxDevAt'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border-top: 1px solid #333; border-right: 1px solid #333; border-left: 1px solid #333;">
                                    Dist(To&Fro)(Kms):</td>
                                <td colspan="1" style="border-top: 1px solid #333; border-right: 1px solid #333; border-left: 1px solid #333;">
                                    {{ $jcrs['distTo_FroKms'] ? $jcrs['distTo_FroKms'].' m' : '---' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td colspan="3" valign="top" style="border: 1px solid #333; cellspacing:0; cellpadding:0; padding: 0;">
                    <table style="width: 100%; border:0; cellspacing:0; cellpadding:0;">
                        <thead>
                            <th colspan="3" align="center" style="border: 1px solid #333;background-color: #333; color: white; font-weight:600;">
                                MUD PARAMETER
                            </th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Rm: </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    <span>
                                        {{ $jcrs['rm'] ? $jcrs['rm'] : '---' }}
                                        <span>Ohm m at</span>
                                        <span>
                                            {{ $jcrs['rmTemp'] ? $jcrs['rmTemp'].' F' : '---' }}
                                        </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Rmf: </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    <span>
                                        {{ $jcrs['rmf'] ? $jcrs['rmf'] : '---' }}
                                        <span>Ohm m at</span>
                                        <span>
                                            {{ $jcrs['rmfTemp'] ? $jcrs['rmfTemp'].' F' : '---' }}
                                        </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Rmc: </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    <span>
                                        {{ $jcrs['rmc'] ? $jcrs['rmc'] : '---' }}
                                        <span>Ohm m at</span>
                                        <span>
                                            {{ $jcrs['rmcTemp'] ? $jcrs['rmcTemp'].' F' : '---' }}
                                        </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">BHT: </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    <span>
                                        {{ $jcrs['bht'] ? $jcrs['bht'] : '---' }}
                                    </span>
                                    <span>F at</span>
                                    <span>
                                        {{ $jcrs['bhtDepth'] ? $jcrs['bhtDepth'].' m' : '---' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Specific Gravity: </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['spgr'] ? $jcrs['spgr'].' gm/cc' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Viscosity:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['viscosity'] ? $jcrs['viscosity'].' CP' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Mud Type:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['mudType'] ? $jcrs['mudType'] : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Water Loss:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['waterloss'] ? $jcrs['waterloss'].' cc' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">PH:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['ph'] ? $jcrs['ph'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">OIL%:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['oilpercnt'] ? $jcrs['oilpercnt'].'%' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">KCL/Barytes</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['kcl_barytes'] ? $jcrs['kcl_barytes'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Salinity</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['salinity'] ? $jcrs['salinity'].' gpl' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border-top: 1px solid #333; border-right: 1px solid #333; border-left: 1px solid #333;">Last
                                    Circulation</td>
                                <td colspan="2" style="border-top: 1px solid #333; border-right: 1px solid #333; border-left: 1px solid #333;">
                                    {{ $jcrs['lastcirc'] ? $jcrs['lastcirc'] : '---' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td colspan="5" valign="top" style="border: 1px solid #333; cellspacing:0; cellpadding:0; padding: 0;">
                    <table style="width: 100%; border:0; cellspacing:0; cellpadding:0;">
                        <thead>
                            <th colspan="5" align="center" style="border: 1px solid #333;background-color: #333; color: white; font-weight:600;">
                                CABLE INFORMATION
                            </th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">SIZE(inch):</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['cableSize'] }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Insulation:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['insulation'] }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Shoe Date:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['shoeDate'] ? date("d-m-Y", strtotime($jcrs['shoeDate'])) : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Weak Point:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['weakPoint'] }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Cable Head Size:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['cableHeadSize'] }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Cable Length (m):</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['cableLength'] ? $jcrs['cableLength'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Initial Length (m):</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcrs['initialLength'] ? $jcrs['initialLength'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" align="center" style="border: 1px solid #333; background-color: #333; color: white; font-weight:600;">EQUIPMENT STATUS</td>
                            </tr>
                            <tr>
                                <td colspan="4">Surface Equipment:</td>
                                <td colspan="1">
                                    {{ $jcrs['surfaceEquipment'] ? $jcrs['surfaceEquipment'] : 'OK' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="border-right: 0">Auto:</td>
                                <td colspan="1">
                                    {{ $jcrs['automobile'] ? $jcrs['automobile'] : 'OK' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">Well Condition:</td>
                                <td colspan="1">
                                    {{ $jcrs['wellCondition'] ? $jcrs['wellCondition'] : 'OK' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">Time Loss:</td>
                                <td colspan="1">
                                    {{ $jcrs['timeLoss'] ? $jcrs['timeLoss'] : 'NO' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td colspan="3" valign="top" style="border: 1px solid #333; padding: 0;">
                    <table style="width: 100%; border:0; cellspacing:0; cellpadding:0;">
                        <thead>
                            <th colspan="3" align="center" style="border: 1px solid #333; background-color: #333; color: white; font-weight:600;">
                                PERSONNEL</th>
                        </thead>
                        <tbody>
                            @foreach ($jcrs->users as $personnel)
                                <tr>
                                    <td>
                                    {{ $personnel->name }}; 
                                    </td>
                                    <td>
                                    CPF: {{ str($personnel->cpf) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td colspan="2" valign="top" style="border: 1px solid #333; padding: 0;">
                    <table style="width: 100%; border:0; cellspacing:0; cellpadding:0;">
                        <tr>
                            <td colspan="2" align="center" style="border: 1px solid #333;">Information as per sheet attached</td>
                        </tr>
                        <tr rowspan="2">
                            <td colspan="1" style="border: 1px solid #333;">ATTEMPTED:</td>
                            <td colspan="1" style="border: 1px solid #333;">
                                {{ $jcrs['attempted'] ? $jcrs['attempted'] : '---' }}
                            </td>
                        </tr>
                        <tr rowspan="2">
                            <td colspan="1" style="border: 1px solid #333;">RECOVERED:</td>
                            <td colspan="1" style="border: 1px solid #333;">
                                {{ $jcrs['recovered'] ? $jcrs['recovered'] : '---' }}
                            </td>
                        </tr>
                        <tr rowspan="2">
                            <td colspan="1" style="border: 1px solid #333;">MISS FIRE:</td>
                            <td colspan="1" style="border: 1px solid #333;">
                                {{ $jcrs['missFire'] ? $jcrs['missFire'] : '---' }}
                            </td>
                        </tr>
                        <tr rowspan="2">
                            <td colspan="1" style="border: 1px solid #333;">BARREL LOST:</td>
                            <td colspan="1" style="border: 1px solid #333;">
                                {{ $jcrs['barrelLost'] ? $jcrs['barrelLost'] : '---' }}
                            </td>
                        </tr>
                        <tr rowspan="2">
                            <td colspan="1" style="border: 1px solid #333;">EMPTY BARREL:</td>
                            <td colspan="1" style="border: 1px solid #333;">
                                {{ $jcrs['emptyBarrel'] ? $jcrs['emptyBarrel'] : '---' }}
                            </td>
                        </tr>
                        <tr rowspan="2">
                            <td colspan="1" style="border: 1px solid #333;">CHARGE USED:</td>
                            <td colspan="1" style="border: 1px solid #333;">
                                {{ $jcrs['chargeUsed'] ? $jcrs['chargeUsed'] : '---' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="09" align="center" style="border: 1px solid #333; background-color: #333; color: white; font-weight:600;">
                    LOGGING INFORMATION</td>
                <td colspan="12" align="center" style="border: 1px solid #333; background-color: #333; color: white; font-weight:600;">
                    PERFORATION INFORMATION</td>
            </tr>
            <tr>
                <!-- Logging Information -->
                <td colspan="1" rowspan="2" align="center" style="border: 1px solid #333; font-weight:600;">RUN</td>
                <td colspan="2" rowspan="2" align="center" style="border: 1px solid #333; font-weight:600;">Type of Log
                    Recorded</td>
                <td colspan="2" align="center" style="border: 1px solid #333; font-weight:600;">Interval (m)</td>
                <td colspan="3" rowspan="2" align="center" style="border: 1px solid #333; font-weight:600;">Tool No.</td>
                <td colspan="1" rowspan="2" align="center" style="border: 1px solid #333; font-weight:600;">Log Quality G,S,B
                </td>
                <!-- Perforation Information -->
                <td colspan="1" rowspan="2" align="center" style="border: 1px solid #333; font-weight:600;">RUN</td>
                <td colspan="2" align="center" style="border: 1px solid #333; font-weight:600;">Perf/BP/Shot Int.</td>
                <td colspan="3" align="center" style="border: 1px solid #333; font-weight:600;">Charges</td>
                <td colspan="3" align="center" style="border: 1px solid #333; font-weight:600;">Prima Chord</td>
                <td colspan="2" align="center" style="border: 1px solid #333; font-weight:600;">Fuses</td>
                <td colspan="1" rowspan="2" align="center" style="border: 1px solid #333; font-weight:600;">F/MF</td>
            </tr>
            <tr>
                <td colspan="1" align="center" style="border: 1px solid #333;">Bottom</td>
                <td colspan="1" align="center" style="border: 1px solid #333;">Top</td>
                <td colspan="1" align="center" style="border: 1px solid #333;">Bottom</td>
                <td colspan="1" align="center" style="border: 1px solid #333;">Top</td>
                <td colspan="2" align="center" style="border: 1px solid #333;">Type</td>
                <td colspan="1" align="center" style="border: 1px solid #333;">No.</td>
                <td colspan="2" align="center" style="border: 1px solid #333;">Type</td>
                <td colspan="1" align="center" style="border: 1px solid #333;">Length (m)</td>
                <td colspan="1" align="center" style="border: 1px solid #333;">Type</td>
                <td colspan="1" align="center" style="border: 1px solid #333;">No.</td>
            </tr>
            @if (empty($jcrs->logs))
                <tr style="height: 10rem;">
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="2" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="3" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="2" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="2" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                </tr>
            @else
            @foreach ($jcrs->logs as $log)
            <tr>
            <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['runNo'] }}</td>
            <td colspan="2" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['logRecorded'] }}</td>
            <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['bottomDepth'] }}</td>
            <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['topDepth'] }}</td>
            <td colspan="3" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['toolNo'] }}</td>
            <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['logQuality'] }}</td>
            @if (str_contains(strtoupper($log['logRecorded']), 'PERFORATION') | str_contains(strtoupper($log['logRecorded']), 'TTP') | str_contains(strtoupper($log['logRecorded']), 'BP') | str_contains(strtoupper($log['logRecorded']), 'BRIDGE PLUG'))
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['runNo'] }}</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['bottomShotDepth'] }}</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['topShotDepth'] }}</td>
                <td colspan="2" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['charge'] }}</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['chargeNo'] }}</td>
                <td colspan="2" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['primaChord'] }}</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['primaChordQty'] }}</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['fuse'] }}</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['fuseNo'] }}</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">{{ $log['fMf'] }}</td>
            @else
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="2" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="2" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                <td colspan="1" align="center" style="border-left: 1px solid #333; border-right: 1px solid #333;">---</td>
                </tr>
            @endif
                @endforeach
            @endif
            <tr>
                <td colspan="05" align="center" style="border: 1px solid #333; background-color: #333; color: white; font-weight:600;">
                    REMARKS</td>
                <td colspan="06" align="center" style="border: 1px solid #333; background-color: #333; color: white; font-weight:600;">
                    CONSUMPTION OF EXPLOSIVE</td>
                <td colspan="05" align="center" style="border: 1px solid #333; background-color: #333; color: white; font-weight:600;">
                    PRODUCTION LOGGING</td>
                <td colspan="05" align="center" style="border: 1px solid #333; background-color: #333; color: white; font-weight:600;">
                    HSE DATA</td>
            </tr>
            <tr>
                <td colspan="05" rowspan="1" style="border: 1px solid #333; padding: 5px; word-wrap: break-sentence; justify-content: center;">
                    {{ $jcrs['remarks'] }}
                </td>
                <td colspan="06" valign="top" style="border: 1px solid #333; cellspacing:0; cellpadding:0; padding: 0;">
                    <table style="width: 100%; height: 100% border:0; cellspacing:0; cellpadding:0;">
                        <thead>
                            <th colspan="03" align="center" style="border: 1px solid #333;">TYPE</th>
                            <th colspan="01" align="center" style="border: 1px solid #333;">ISSUED</th>
                            <th colspan="01" align="center" style="border: 1px solid #333;">USED</th>
                            <th colspan="01" align="center" style="border: 1px solid #333;">BALANCE</th>
                        </thead>
                        @if (count($jcrs->explosives) >= 6)
                            @foreach ($jcrs->explosives as $explosive)
                            <tr>
                      <td colspan="03" style="border: 1px solid #333;">{{ $explosive['explosive'] }}</td>
                      <td colspan="01" style="border: 1px solid #333;">{{ $explosive['issued'] }}</td>
                      <td colspan="01" style="border: 1px solid #333;">{{ $explosive['used'] }}</td>
                      <td colspan="01" style="border: 1px solid #333;">{{ $explosive['returned'] }}</td>
                    </tr>
                    @endforeach
                    @else
                    @foreach ($jcrs->explosives as $explosive)
                    <tr>
                        <td colspan="03" style="border: 1px solid #333;">{{ $explosive['explosive'] }}</td>
                        <td colspan="01" style="border: 1px solid #333;">{{ $explosive['issued'] }}</td>
                        <td colspan="01" style="border: 1px solid #333;">{{ $explosive['used'] }}</td>
                        <td colspan="01" style="border: 1px solid #333;">{{ $explosive['returned'] }}</td>
                    </tr>
                    @endforeach
                    @for ($i = count($jcrs->explosives); $i < 6; ++$i)
                    <tr>
                  <td colspan="03" style="border: 1px solid #333;">---</td>
                  <td colspan="01" style="border: 1px solid #333;">---</td>
                  <td colspan="01" style="border: 1px solid #333;">---</td>
                  <td colspan="01" style="border: 1px solid #333;">---</td>
                  </tr>
                    @endfor
                    @endif
                    </table>
                </td>
                <td colspan="05" valign="top" style="border-left: 1px solid #333;">
                    <table style="width: 100%; border:0; cellspacing:0; cellpadding:0;">
                        <tr>
                            <td>Objective:</td>
                        </tr>
                        <tr>
                            <td rowspan="3" colspan="05">
                                {{ $jcrs['objective'] }}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Observation:</td>
                        </tr>
                        <tr>
                            <td rowspan="3" colspan="05">
                                {{ $jcrs['observations'] }}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    </table>
                </td>
                <td colspan="08" style="border: 1px solid #333; padding: 0;">
                    <table style="top: 0; width: 100%; height: 100%; border:0; cellspacing:0; cellpadding:0;">
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">1) Permit Type</td>
                            <td colspan="05" style="border: 1px solid #333;">
                                {{ $jcrs['permitType'] }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">2) Permit No.</td>
                            <td colspan="03" style="border: 1px solid #333;">
                                {{ $jcrs['permitNo'] }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">3) Form of Permit to Work</td>
                            <td colspan="03" style="border: 1px solid #333;">
                                {{ $jcrs['permitWork']==1 ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">4) Electrical Lockout</td>
                            <td colspan="03" style="border: 1px solid #333;">
                                {{ $jcrs['elecLockout']==1 ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">5) Safety meeting conducted</td>
                            <td colspan="03" style="border: 1px solid #333;">
                                {{ $jcrs['safetyMeeting']==1 ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">6) Job Closeup Meeting</td>
                            <td colspan="03" style="border: 1px solid #333;">
                                {{ $jcrs['jobCloseMeeting']==1 ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">7) Near Miss</td>
                            <td colspan="03" style="border: 1px solid #333;">
                                {{ $jcrs['nearMiss']==1 ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="05" style="border-top: 1px solid #333; border-left: 1px solid #333;"></td>
                <td colspan="06" style="border-top: 1px solid #333; border-left: 1px solid #333;"></td>
                <td colspan="05" style="border-top: 1px solid #333; border-left: 1px solid #333;"></td>
                <td colspan="05" rowspan="1" style="border-top: 1px solid #333; border-left: 1px solid #333; border-right: 1px solid #333;">Near Miss Brief
                    Description:</td>
            </tr>
            <tr>
                <td colspan="03" style="border-bottom: 1px solid #333; border-left: 1px solid #333;">Date:
                    {{ $jcrs['created_at'] }}
                </td>
                <td colspan="03" style="border-bottom: 1px solid #333;">Sign:
                    {{ $jcrs['created_by'] }}
                </td>
                <td colspan="02" style="border-bottom: 1px solid #333; border-left: 1px solid #333;">Date:</td>
                <td colspan="03" style="border-bottom: 1px solid #333;">Group Head Name</td>
                <td colspan="02" style="border-bottom: 1px solid #333; border-left: 1px solid #333;">Date:
                    {{ $jcrs['final_submitted_at'] }}
                </td>
                <td colspan="03" style="border-bottom: 1px solid #333;">I/C-Ops. Name:
                    {{ $jcrs['final_submitted_by'] }}
                </td>
                <td colspan="05" rowspan="1" style="border-bottom: 1px solid #333; border-left: 1px solid #333; border-right: 1px solid #333;">
                    {{ $jcrs['nearMiss'] ? $jcrs['nearMissDesc'] : 'N/A' }}
                </td>
            </tr>

        </tbody>
    </table>
    <pdf:nextpage name="contingents" />
    <h2 style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:25px;">Contingents</h2>
    <p>
        {{ $jcrs['contingents'] }}
    </p>

</body>

</html>