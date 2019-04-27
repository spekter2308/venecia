<div class="title">
    <span>{{trans('messages.size_table')}}</span>
    <a onclick="HidePopupMessage()">{{trans('messages.close')}} <i class="fa fa-times"
                                                                   aria-hidden="true"></i></a>
</div>
<div class="info-m">
    {{  trans('messages.info_info')}}
</div>
<div class="text-menu">
    <div>{{trans('messages.info_text')}}</div>
    <p>
        <a onclick="switch_type(this.id)" id="info-woman"> <b> {{trans('messages.info_type_w')}}</b></a>
        <a onclick="switch_type(this.id)" id="info-man"> <b> {{trans('messages.info_type')}}</b></a>
    </p>
   
    <table cellspacing="0" cellpadding="0" class="p_table" id="womn-inf">
        <tbody>
        <tr>
            <td class="first">{{trans('messages.info_length')}}</td>
            <td>23</td>
            {{--<td class="bw">23,7</td>--}}
            <td>24</td>
            {{--<td class="bw">24,5</td>--}}
            <td>24,5</td>
           {{-- <td class="bw">25,4</td>--}}
            <td>25</td>
           {{-- <td class="bw">25,4</td>--}}
            <td>26</td>
            {{--<td class="bw">26,4</td>--}}
            <td>26,5</td>
        </tr>
        <tr>
            <td class="first">{{trans('messages.info_rozmir')}}</td>
            <td>36</td>
            {{--<td class="bw">36</td>--}}
            <td>37</td>
            {{--<td class="bw">37,5</td>--}}
            <td>38</td>
            {{--<td class="bw">38,5</td>--}}
            <td>39</td>
           {{-- <td class="bw">39,5</td>--}}
            <td>40</td>
            {{--<td class="bw">40</td>--}}
            <td>41</td>
        </tr>
        </tbody>
    </table>
    
    <table cellspacing="0" cellpadding="0" class="p_table" id="man-inf">
        <tbody>
        <tr>
            <td class="first"> {{trans('messages.info_length')}}</td>
            <td>28,0</td>
            <td>28,6</td>
            <td>29,3</td>
            <td>30,0</td>
            <td>30,7</td>
            <td>31,3</td>
        </tr>
        <tr>
            <td class="first">{{trans('messages.info_rozmir')}}</td>
            <td>40</td>
            <td>41</td>
            <td>42</td>
            <td>43</td>
            <td>44</td>
            <td>45</td>
        </tr>
        </tbody>
    </table>
</div>

<script>
    function HidePopupMessage() {
        $( "#shoes_size_popup" ).empty();
        $('#shoes_size_popup').hide(100);
    }

    function switch_type(e) {
        if (e == 'info-man') {
            $('#womn-inf').hide();
            $('#man-inf').show();
            $('#info-man').addClass('active');
            $('#info-woman').removeClass('active');
        }
        
        if (e == 'info-woman') {
            $('#womn-inf').show();
            $('#man-inf').hide();
            $('#info-woman').addClass('active');
            $('#info-man').removeClass('active');
        }
       
    }
</script>