
                        <tbody>
                        @if(isset($final_opening->withSpecifications) && count($final_opening->withSpecifications))
                            @php($n=1)
                            @foreach($final_opening->withSpecifications as $opm)
                                <tr>
                                    <td>{!! $n++ !!}</td>
                                    <td>{!! date('d-M-Y', strtotime($opm->date)) !!}</td>
                                    <td>{!! $opm->asset->materials_name !!} ({!! $opm->asset->recourse_code !!})</td>
                                    <td>{!! $opm->specification->specification !!}</td>
                                    <td>{!! $opm->asset->unit !!}</td>
                                    <td>{!! $opm->rate !!}</td>
                                    <td>{!! $opm->qty !!}</td>
                                    <td>{!! (float)($opm->qty * $opm->rate) !!}</td>
                                    <td>{!! (isset($opm->purpose))?$opm->purpose:'' !!}</td>
                                    <td>{!! (isset($opm->remarks))?$opm->remarks:'' !!}</td>
                                    <td>
                                        <button class="text-success border-0 inline-block bg-none" ref="{!! $opm->id !!}" onclick="return Obj.fixedAssetOpeningSpecEdit(this)"><i class="fas fa-edit"></i></button>
                                        <input type="hidden" name="id" value="{!! $opm->id !!}">
                                        <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
