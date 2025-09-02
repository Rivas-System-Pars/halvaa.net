<div class="table-responsive">
    <table class="table">
        <tbody>

            <tr>
                <th scope="row" style="width: 100px;">نام</th>
                <td>{{ $contact->name }}</td>

            </tr>

            <tr>
                <th scope="row">موضوع</th>
                <td>{{ $contact->subject }}</td>
            </tr>
            <tr>
                <th scope="row">ایمیل</th>
                <td>{{ $contact->email }}</td>
            </tr>
            <tr>
                <th scope="row">شماره تلفن</th>
                <td>{{ $contact->mobile }}</td>
            </tr>

            <tr>
                <th scope="row">تاریخ ارسال</th>
                <td>{{ jdate($contact->created_at) }}</td>
            </tr>

            <tr>
                <th scope="row">پیام</th>
                <td>{{ $contact->message }}</td>
            </tr>

            <tr>
                <th scope="row">عکس</th>
                <td>
                    @if($contact->image)
                        <img src="{{ asset($contact->image) }}"
                             alt="تصویر پیوست"
                             style="max-width:150px; border-radius:6px; border:1px solid #eee; padding:3px;">
                        <div style="margin-top:5px;">
                            <a href="{{ asset($contact->image) }}"></a>
                        </div>
                    @else
                        <span style="color:#888;">هیچ تصویری بارگذاری نشده</span>
                    @endif
                </td>
            </tr>

        </tbody>
    </table>
</div>
