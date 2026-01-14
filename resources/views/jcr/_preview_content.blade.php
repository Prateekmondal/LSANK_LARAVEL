<div class="table-responsive">
<table class="table">
    <tbody>
        <tr class="text-sm text-md">
            <td class="w-25">
                <img class="img-fluid" src="/static/images/ongc.png" style="max-width: 80px;"/>
            </td>
            <td class="fw-semibold w-25">
                Well Logging Services<br>ONGC, Ankleshwar</td>
            <td class="fw-bold w-25">
                JOB COMPLETION REPORT</td>
            <td class="w-25">&nbsp;</td>
        </tr>
        </tbody>
    </table>
    <table style="width: 100%; border:0; cellspacing:0; cellpadding:0;">
        <tr>
            <td colspan="2" align="right">Indent No.:</td>
            <td colspan="1">
                {{ $jcr['indentNo'] }}
            </td>
            <td colspan="1">&nbsp;</td>
            <td colspan="1" align="right">Job No.:</td>
            <td colspan="1">
                {{ $jcr['jobNo'] }}
            </td>
            <td colspan="3">&nbsp;</td>
            <td colspan="3">&nbsp;</td>
            <td colspan="5">
                <table>
                    <tr>
                        <td colspan="3"><span>Job Status:</span><span>
                                {{ $jcr['jobStatus'] }}
                            </span></td>
                    </tr>
                    <tr>
                        <td colspan="3"><span>Logging Type:</span><span>
                                {{ $jcr['loggingType'] }}
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
                    {{ date("d-m-Y", strtotime($jcr['workOrderDate'])) }}
                </td>
                <td colspan="1" align="right" style="border: 1px solid #333;">Well No./Code:</td>
                <td colspan="2" align="left" style="border: 1px solid #333;">
                    {{ $jcr['wellNo'] }}
                </td>
                <td colspan="1" align="right" style="border: 1px solid #333;">Rig No.:</td>
                <td colspan="1" align="left" style="border: 1px solid #333;">
                    {{ $jcr['rigNo'] }}
                </td>
                <td colspan="1" align="right" style="border: 1px solid #333;">Unit No.:</td>
                <td colspan="2" align="left" style="border: 1px solid #333;">
                    {{ $jcr['unitNo'] }}
                </td>
                <td colspan="2" align="right" style="border: 1px solid #333;">Mast/Van No.:</td>
                <td colspan="3" align="left" style="border: 1px solid #333;">
                    {{ $jcr['mastVanNo'] }}
                </td>
                <td colspan="2" align="right" style="border: 1px solid #333;">Well Type:</td>
                <td colspan="1" align="left" style="border: 1px solid #333;">
                    {{ $jcr['wellType'] }}
                </td>
                <td colspan="3" rowspan="2" align="center" style="background-color: #333; color: white; border-top: 1px solid #333; border-right: 1px solid #333;">SIDE
                    WALL CORE (SWC)
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right" style="border: 1px solid #333;">Date of Job:</td>
                <td colspan="1" align="left" style="border: 1px solid #333;">
                    {{ date("d-m-Y", strtotime($jcr['jobDate'])) }}
                </td>
                <td colspan="1" align="right" style="border: 1px solid #333;">Field/Area:</td>
                <td colspan="2" align="left" style="border: 1px solid #333;">
                    {{ $jcr['fieldName'] }}
                </td>
                <td colspan="1" align="center" style="border: 1px solid #333;">KB:
                    {{ $jcr['kb'] ? $jcr['kb'].'m' : '---' }}
                </td>
                <td colspan="1" align="center" style="border: 1px solid #333;">GL:
                    {{ $jcr['gl'] ? $jcr['gl'].' m' : '---' }}
                </td>
                <td colspan="1" align="center" style="border: 1px solid #333;">
                    {{ $jcr['logType'] }}
                </td>
                <td colspan="2" align="center" style="border: 1px solid #333;">Well Owner:
                    {{ $jcr['wellOwner'] }}
                </td>
                <td colspan="2" align="right" style="border: 1px solid #333;">LV No.:</td>
                <td colspan="3" align="left" style="border: 1px solid #333;">
                    {{ $jcr['lvNo'] }}
                </td>
                <td colspan="3" align="center" style="border: 1px solid #333;">Rig Type:
                    {{ $jcr['rigType'] }}
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
                                    {{ date("H:i", strtotime($jcr['assembled_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcr['assembled_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Departure office:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcr['depOffice_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcr['depOffice_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Arrival Site:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcr['arrivalSite_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcr['arrivalSite_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Indented:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcr['indented_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcr['indented_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Well Readiness:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcr['wellReadiness_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcr['wellReadiness_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Well Taken:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcr['wellTaken_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcr['wellTaken_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Rig Up:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcr['rigUP_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcr['rigUP_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Well Hand Over:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcr['wellHandOver_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcr['wellHandOver_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Departure Site:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcr['depSite_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcr['depSite_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Arrival Office:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("H:i", strtotime($jcr['arrivalOffice_time'])) }}
                                </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ date("d-m-Y", strtotime($jcr['arrivalOffice_date'])) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Preparation Time:</td>
                                <td colspan="4" style="border: 1px solid #333;">
                                    {{ $jcr['preparationTime'] ? $jcr['preparationTime'].' HRS.' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #333;">Post Proce. Time:</td>
                                <td colspan="4" style="border: 1px solid #333;">
                                    {{ $jcr['postProceTime'] ? $jcr['postProceTime'].' HRS.' : '---' }}
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
                                <td colspan="1" style="border: 1px solid #333;">{{ $jcr['depthDriller'] ? $jcr['depthDriller'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Depth Logger:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcr['depthLogger'] ? $jcr['depthLogger'].' m' : '---' }}
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Casing Size(inch):</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcr['casingSize'] ? $jcr['casingSize'].'"' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">C/Shoe Driller:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcr['casingShoeDriller'] ? $jcr['casingShoeDriller'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">C/Shoe Logger:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcr['casingShoeLogger'] ? $jcr['casingShoeLogger'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Float Collar:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcr['floatCollar'] ? $jcr['floatCollar'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Bit Size(inch):</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcr['bitSize'] ? $jcr['bitSize'].' "' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Tubing Size(inch):</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcr['tubingSize'] ? $jcr['tubingSize'].' "' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">T/Shoe/Packer:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcr['t_shoe_packer'] ? $jcr['t_shoe_packer'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">S/Nipple Top Exp.:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcr['s_nippletopexp'] ? $jcr['s_nippletopexp'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">THP</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcr['THP'] ? $jcr['THP'].' PSI' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Max Dev at:</td>
                                <td colspan="1" style="border: 1px solid #333;">
                                    {{ $jcr['maxDevAt'] ? $jcr['maxDevAt'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border-top: 1px solid #333; border-right: 1px solid #333; border-left: 1px solid #333;">
                                    Dist(To&Fro)(Kms):</td>
                                <td colspan="1" style="border-top: 1px solid #333; border-right: 1px solid #333; border-left: 1px solid #333;">
                                    {{ $jcr['distTo_FroKms'] ? $jcr['distTo_FroKms'].' m' : '---' }}
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
                                        {{ $jcr['rm'] ? $jcr['rm'] : '---' }}
                                        <span>Ohm m at</span>
                                        <span>
                                            {{ $jcr['rmtemp'] ? $jcr['rmtemp'].' F' : '---' }}
                                        </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Rmf: </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    <span>
                                        {{ $jcr['rmf'] ? $jcr['rmf'] : '---' }}
                                        <span>Ohm m at</span>
                                        <span>
                                            {{ $jcr['rmftemp'] ? $jcr['rmftemp'].' F' : '---' }}
                                        </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Rmc: </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    <span>
                                        {{ $jcr['rmc'] ? $jcr['rmc'] : '---' }}
                                        <span>Ohm m at</span>
                                        <span>
                                            {{ $jcr['rmctemp'] ? $jcr['rmctemp'].' F' : '---' }}
                                        </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">BHT: </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    <span>
                                        {{ $jcr['bht'] ? $jcr['bht'] : '---' }}
                                    </span>
                                    <span>F at</span>
                                    <span>
                                        {{ $jcr['bhtdepth'] ? $jcr['bhtdepth'].' m' : '---' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Specific Gravity: </td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['spgr'] ? $jcr['spgr'].' gm/cc' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Viscosity:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['viscosity'] ? $jcr['viscosity'].' CP' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Mud Type:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['mudType'] ? $jcr['mudType'] : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Water Loss:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['waterloss'] ? $jcr['waterloss'].' cc' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">PH:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['ph'] ? $jcr['ph'].'' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">OIL%:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['oilpercnt'] ? $jcr['oilpercnt'].'%' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">KCL/Barytes</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['kcl_barytes'] ? $jcr['kcl_barytes'].' %' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border: 1px solid #333;">Salinity</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['salinity'] ? $jcr['salinity'].' gpl' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="border-top: 1px solid #333; border-right: 1px solid #333; border-left: 1px solid #333;">Last
                                    Circulation</td>
                                <td colspan="2" style="border-top: 1px solid #333; border-right: 1px solid #333; border-left: 1px solid #333;">
                                    {{ $jcr['lastcirc_from'] ? date("d-m-Y H:i", strtotime($jcr['lastcirc_from'])) : '---' }} to
                                    {{ $jcr['lastcirc_to'] ? date("d-m-Y H:i", strtotime($jcr['lastcirc_to'])) : '---' }}
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
                                    {{ $jcr['cableSize'] }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Insulation:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['insulation'] }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Shoe Date:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['shoeDate'] ? date("d-m-Y", strtotime($jcr['shoeDate'])) : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Weak Point:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['weakPoint'] }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Cable Head Size:</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['cableHeadSize'] }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Cable Length (m):</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['cableLength'] ? $jcr['cableLength'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="border: 1px solid #333;">Initial Length (m):</td>
                                <td colspan="2" style="border: 1px solid #333;">
                                    {{ $jcr['initialLength'] ? $jcr['initialLength'].' m' : '---' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" align="center" style="border: 1px solid #333; background-color: #333; color: white; font-weight:600;">EQUIPMENT STATUS</td>
                            </tr>
                            <tr>
                                <td colspan="4">Surface Equipment:</td>
                                <td colspan="1">
                                    {{ $jcr['surfaceEquipment'] ? $jcr['surfaceEquipment'] : 'OK' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="border-right: 0">Auto:</td>
                                <td colspan="1">
                                    {{ $jcr['automobile'] ? $jcr['automobile'] : 'OK' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">Well Condition:</td>
                                <td colspan="1">
                                    {{ $jcr['wellCondition'] ? $jcr['wellCondition'] : 'OK' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">Time Loss:</td>
                                <td colspan="1">
                                    {{ $jcr['timeLoss'] ? $jcr['timeLoss'] : 'NO' }}
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
                            @foreach ($jcr->users as $personnel)
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
                                {{ $jcr['attempted'] ? $jcr['attempted'] : '---' }}
                            </td>
                        </tr>
                        <tr rowspan="2">
                            <td colspan="1" style="border: 1px solid #333;">RECOVERED:</td>
                            <td colspan="1" style="border: 1px solid #333;">
                                {{ $jcr['recovered'] ? $jcr['recovered'] : '---' }}
                            </td>
                        </tr>
                        <tr rowspan="2">
                            <td colspan="1" style="border: 1px solid #333;">MISS FIRE:</td>
                            <td colspan="1" style="border: 1px solid #333;">
                                {{ $jcr['missFire'] ? $jcr['missFire'] : '---' }}
                            </td>
                        </tr>
                        <tr rowspan="2">
                            <td colspan="1" style="border: 1px solid #333;">BARREL LOST:</td>
                            <td colspan="1" style="border: 1px solid #333;">
                                {{ $jcr['barrelLost'] ? $jcr['barrelLost'] : '---' }}
                            </td>
                        </tr>
                        <tr rowspan="2">
                            <td colspan="1" style="border: 1px solid #333;">EMPTY BARREL:</td>
                            <td colspan="1" style="border: 1px solid #333;">
                                {{ $jcr['emptyBarrel'] ? $jcr['emptyBarrel'] : '---' }}
                            </td>
                        </tr>
                        <tr rowspan="2">
                            <td colspan="1" style="border: 1px solid #333;">CHARGE USED:</td>
                            <td colspan="1" style="border: 1px solid #333;">
                                {{ $jcr['chargeUsed'] ? $jcr['chargeUsed'] : '---' }}
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
            @if (empty($jcr->logs))
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
            @foreach ($jcr->logs as $log)
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
                    {{ $jcr['remarks'] }}
                </td>
                <td colspan="06" valign="top" style="border: 1px solid #333; cellspacing:0; cellpadding:0; padding: 0;">
                    <table style="width: 100%; height: 100% border:0; cellspacing:0; cellpadding:0;">
                        <thead>
                            <th colspan="03" align="center" style="border: 1px solid #333;">TYPE</th>
                            <th colspan="01" align="center" style="border: 1px solid #333;">ISSUED</th>
                            <th colspan="01" align="center" style="border: 1px solid #333;">USED</th>
                            <th colspan="01" align="center" style="border: 1px solid #333;">BALANCE</th>
                        </thead>
                        @if (count($jcr->explosives) >= 6)
                            @foreach ($jcr->explosives as $explosive)
                            <tr>
                      <td colspan="03" style="border: 1px solid #333;">{{ $explosive['explosive'] }}</td>
                      <td colspan="01" style="border: 1px solid #333;">{{ $explosive['issued'] }}</td>
                      <td colspan="01" style="border: 1px solid #333;">{{ $explosive['used'] }}</td>
                      <td colspan="01" style="border: 1px solid #333;">{{ $explosive['returned'] }}</td>
                    </tr>
                    @endforeach
                    @else
                    @foreach ($jcr->explosives as $explosive)
                    <tr>
                        <td colspan="03" style="border: 1px solid #333;">{{ $explosive['explosive'] }}</td>
                        <td colspan="01" style="border: 1px solid #333;">{{ $explosive['issued'] }}</td>
                        <td colspan="01" style="border: 1px solid #333;">{{ $explosive['used'] }}</td>
                        <td colspan="01" style="border: 1px solid #333;">{{ $explosive['returned'] }}</td>
                    </tr>
                    @endforeach
                    @for ($i = count($jcr->explosives); $i < 6; ++$i)
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
                                {{ $jcr['objective'] }}
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
                                {{ $jcr['observations'] }}
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
                                {{ $jcr['permitType'] }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">2) Permit No.</td>
                            <td colspan="03" style="border: 1px solid #333;">
                                {{ $jcr['permitNo'] }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">3) Form of Permit to Work</td>
                            <td colspan="03" style="border: 1px solid #333;">
                                {{ $jcr['permitWork']==1 ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">4) Electrical Lockout</td>
                            <td colspan="03" style="border: 1px solid #333;">
                                {{ $jcr['elecLockout']==1 ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">5) Safety meeting conducted</td>
                            <td colspan="03" style="border: 1px solid #333;">
                                {{ $jcr['safetyMeeting']==1 ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">6) Job Closeup Meeting</td>
                            <td colspan="03" style="border: 1px solid #333;">
                                {{ $jcr['jobCloseMeeting']==1 ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="03" style="border: 1px solid #333;">7) Near Miss</td>
                            <td colspan="03" style="border: 1px solid #333;">
                                {{ $jcr['nearMiss']==1 ? 'Yes' : 'No' }}
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
                    {{ $jcr['creator_signed_at'] }}
                </td>
                <td colspan="03" style="border-bottom: 1px solid #333;">Sign:
                    {{ $jcr['creator_signature'] }}
                </td>
                <td colspan="02" style="border-bottom: 1px solid #333; border-left: 1px solid #333;">Date: {{ $jcr['party_chief_signed_at'] }}</td>
                <td colspan="03" style="border-bottom: 1px solid #333;">Group Head Name: {{ $jcr['party_chief_signature'] }}</td>
                <td colspan="02" style="border-bottom: 1px solid #333; border-left: 1px solid #333;">Date:
                    {{ $jcr['operation_incharge_signed_at'] }}
                </td>
                <td colspan="03" style="border-bottom: 1px solid #333;">I/C-Ops. Name:
                    {{ $jcr['operation_incharge_signature'] }}
                </td>
                <td colspan="05" rowspan="1" style="border-bottom: 1px solid #333; border-left: 1px solid #333; border-right: 1px solid #333;">
                    {{ $jcr['nearMiss'] ? $jcr['nearMissDesc'] : 'N/A' }}
                </td>
            </tr>

        </tbody>
    </table>
    <pdf:nextpage name="contingents" />
    <h2 style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:25px;">Contractual Crew</h2>
    <p>
        {{ $jcr['contingents'] }}
    </p>
</div>