package com.HOT.star_0733.hot_delivery;

import android.app.Activity;
import android.content.ContentUris;
import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.net.Uri;
import android.os.Build;
import android.os.Environment;
import android.provider.DocumentsContract;
import android.provider.MediaStore;
import android.support.v4.content.CursorLoader;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.util.Patterns;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;
import de.hdodenhof.circleimageview.CircleImageView;
import id.zelory.compressor.Compressor;

public class Register extends AppCompatActivity {

    public static final int PICKFILE_RESULT_CODE = 0733;
    public static final int PICKLICENCE_RESULT_CODE = 10;

    Spinner state,spinner_city;
      AsyncHttpClient client;
      ACProgressFlower dialog;
      ArrayList<String > City;
      TextView txtSignin;
      CircleImageView profile_img;
      Uri photo,doc;
      String photo_path="",doc_path="",cmp_photo_path="",cmp_doc_path="";
      File compressedImage;
      Button btnCreateAccount,document;
      EditText name,email,pass,c_pass,contact,account,ifsc;
      @Override
      protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_register);


            final String[] State =  {"ANDAMAN AND NICOBAR ISLANDS","ANDHRA PRADESH","ARUNACHAL PRADESH",
                        "ASSAM",
                        "BIHAR",
                        "CHANDIGARH",
                        "CHHATTISGARH",
                        "DADRA AND NAGAR HAVELI",
                        "DAMAN AND DIU",
                        "DELHI",
                        "GOA",
                        "GUJARAT",
                        "HARYANA",
                        "HIMACHAL PRADESH",
                        "JAMMU AND KASHMIR",
                        "JHARKHAND",
                        "KARNATAKA",
                        "KERALA",
                        "LAKSHADWEEP",
                        "MADHYA PRADESH",
                        "MAHARASHTRA",
                        "MANIPUR",
                        "MEGHALAYA",
                        "MIZORAM",
                        "NAGALAND",
                        "ODISHA",
                        "PUDUCHERRY",
                        "PUNJAB",
                        "RAJASTHAN",
                        "SIKKIM",
                        "TAMIL NADU",
                        "TELANGANA",
                        "TRIPURA",
                        "UTTAR PRADESH",
                        "UTTARAKHAND",
                        "WEST BENGAL"};

            City = new ArrayList<>();

            initView();

            ArrayAdapter adapter = new ArrayAdapter(Register.this,android.R.layout.simple_spinner_dropdown_item,State);
            state.setAdapter(adapter);

            state.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                  @Override
                  public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                        getCity(adapterView.getItemAtPosition(i).toString());
                  }
                  @Override
                  public void onNothingSelected(AdapterView<?> adapterView) { }
            });

            txtSignin.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        finish();
                        startActivity(new Intent(Register.this,Login.class));
                  }
            });

            btnCreateAccount.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        doValidation();
                  }
            });

          profile_img.setOnClickListener(new View.OnClickListener() {
              @Override
              public void onClick(View view) {
                uploadImage();
              }
          });

          document.setOnClickListener(new View.OnClickListener() {
              @Override
              public void onClick(View view) {
                  uploadLicence();
              }
          });

      }

    private void uploadLicence() {
        Intent chooseFile = new Intent(Intent.ACTION_GET_CONTENT);
        chooseFile.setType("image/*");
        chooseFile = Intent.createChooser(chooseFile, "Choose a Licence photo");
        startActivityForResult(chooseFile, PICKLICENCE_RESULT_CODE);

      }

    private void uploadImage() {
        Intent chooseFile = new Intent(Intent.ACTION_GET_CONTENT);
        chooseFile.setType("image/*");
        chooseFile = Intent.createChooser(chooseFile, "Choose a profile photo");
        startActivityForResult(chooseFile, PICKFILE_RESULT_CODE);
    }

    @Override
    public void onActivityResult(final int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == PICKFILE_RESULT_CODE && resultCode == Activity.RESULT_OK) {
            if (data == null) {
                Toast.makeText(this, "Please select a image.", Toast.LENGTH_SHORT).show();
            } else {

                photo = data.getData();
                photo_path = getPath(Register.this,photo);
                Log.d("---photo_path",photo_path);

                try {
                    compressedImage = new Compressor(this)
                          .setMaxWidth(500)
                          .setMaxHeight(500)
                          .setQuality(75)
                          .setCompressFormat(Bitmap.CompressFormat.JPEG)
                          .setDestinationDirectoryPath(Environment.getExternalStoragePublicDirectory(
                                Environment.DIRECTORY_PICTURES).getAbsolutePath())
                          .compressToFile(new File(photo_path));

                    int photo_l = photo_path.split("/").length;
                    cmp_photo_path = Environment.getExternalStoragePublicDirectory(
                          Environment.DIRECTORY_PICTURES).getAbsolutePath()+"/"+photo_path.split("/")[photo_l-1];
                    Log.d("--cmp-photo",cmp_photo_path);

                } catch (IOException e) {
                    e.printStackTrace();
                }

                try {
                    Bitmap bitmap = MediaStore.Images.Media.getBitmap(getContentResolver(), photo);
                    profile_img.setImageBitmap(bitmap);
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
        else if(requestCode == PICKLICENCE_RESULT_CODE && resultCode == Activity.RESULT_OK){
            if(data == null){
                Toast.makeText(this, "PLease select a image.", Toast.LENGTH_SHORT).show();
            }else {

                doc = data.getData();
                doc_path = getPath(Register.this,doc);
                Log.d("---photo_path",doc_path);
                int l = doc_path.split("/").length;
                document.setText(doc_path.split("/")[l - 1]);

                try {
                    compressedImage = new Compressor(this)
                          .setMaxWidth(500)
                          .setMaxHeight(500)
                          .setQuality(75)
                          .setCompressFormat(Bitmap.CompressFormat.JPEG)
                          .setDestinationDirectoryPath(Environment.getExternalStoragePublicDirectory(
                                Environment.DIRECTORY_PICTURES).getAbsolutePath())
                          .compressToFile(new File(doc_path));

                    cmp_doc_path = Environment.getExternalStoragePublicDirectory(
                          Environment.DIRECTORY_PICTURES).getAbsolutePath()+"/"+doc_path.split("/")[l - 1];
                    Log.d("--cmp-doc",cmp_doc_path);

                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
    }

      private void doValidation() {
            String str_name = name.getText().toString().trim();
            String str_email = email.getText().toString().trim();
            String str_pass = pass.getText().toString().trim();
            String str_c_pass = c_pass.getText().toString().trim();
            String str_contact = contact.getText().toString().trim();
            String str_acc = account.getText().toString().trim();
            String str_ifsc = ifsc.getText().toString().trim();

            if(str_name.isEmpty() && str_email.isEmpty() && str_c_pass.isEmpty() && str_pass.isEmpty() && str_contact.isEmpty() && str_acc.isEmpty() && str_ifsc.isEmpty()){
                  Toast.makeText(this, "Please fill all the details.", Toast.LENGTH_SHORT).show();
            }else if(str_name.isEmpty()){
                  Toast.makeText(this, "Please fill the full name", Toast.LENGTH_SHORT).show();
            }else if (str_email.isEmpty()){
                  Toast.makeText(this, "Please fill email id", Toast.LENGTH_SHORT).show();

            }else if (!Patterns.EMAIL_ADDRESS.matcher(str_email).matches()){
                  Toast.makeText(this, "Please fill valid email id", Toast.LENGTH_SHORT).show();

            }else if ( str_pass.isEmpty()){
                  Toast.makeText(this, "Please fill the password", Toast.LENGTH_SHORT).show();

            }else if(str_c_pass.isEmpty()){
                  Toast.makeText(this, "Please fill the confirm password", Toast.LENGTH_SHORT).show();

            }else if(str_pass.length() < 6){
                  Toast.makeText(this, "Length of password should be 6.", Toast.LENGTH_SHORT).show();
            }else if(!str_pass.equals(str_c_pass)){
                  Toast.makeText(this, "Both password should be same", Toast.LENGTH_SHORT).show();

            }else if(str_contact.isEmpty() || str_contact.length() < 10){
                  Toast.makeText(this, "Please fill valid contact number", Toast.LENGTH_SHORT).show();

            } else if(str_acc.isEmpty() || str_acc.length() < 11){
                  Toast.makeText(this, "Please fill valid account number", Toast.LENGTH_SHORT).show();

            }else if(str_ifsc.isEmpty()){
                  Toast.makeText(this, "Please fill IFSC code.", Toast.LENGTH_SHORT).show();

            }else if(doc == null){
                Toast.makeText(this, "Please select a driving licence.", Toast.LENGTH_SHORT).show();
            }else if(photo == null){
                Toast.makeText(this, "Please select a profile photo.", Toast.LENGTH_SHORT).show();
            }
            else {
                  doRegister(str_name,str_contact,str_email,str_pass,str_acc,str_ifsc);
            }
      }

      private void doRegister(String str_name, String str_contact, final String str_email, String str_pass, String str_acc, String str_ifsc){
            RequestParams params = new RequestParams();
            params.put("name",str_name);
            params.put("email",str_email);
            params.put("pass",str_pass);
            params.put("contact",str_contact);
            params.put("account",str_acc);
            params.put("ifsc",str_ifsc);
          try {
              params.put("photo",new File(cmp_photo_path));
              params.put("document",new File(cmp_doc_path));
          } catch (Exception e) {
              e.printStackTrace();
              Log.d("---try---",e.toString());
          }
          params.put("city",spinner_city.getSelectedItem().toString());
            client.post(CommonUtil.url+"delivery_register.php",params,new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        super.onSuccess(statusCode, headers, response);
                        dialog.dismiss();
                        Log.d("-------svnasvkja-----",response.toString());
                        try {
                              if(response.getString("responce").equals("Success")) {
                                    Toast.makeText(Register.this, "Registration successful.", Toast.LENGTH_SHORT).show();
                                    finish();
                                    startActivity(new Intent(Register.this, Login.class));
                              }
                              else if(response.getString("responce").equals("exist")){
                                    Toast.makeText(Register.this, "User already registered with email : "+str_email, Toast.LENGTH_SHORT).show();
                              }
                              else {
                                    Toast.makeText(Register.this, "Something went wrong.", Toast.LENGTH_SHORT).show();

                              }
                        } catch (JSONException e) {
                              e.printStackTrace();
                        }
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        super.onFailure(statusCode, headers, responseString, throwable);
                        dialog.dismiss();
                        Toast.makeText(Register.this, ""+responseString, Toast.LENGTH_SHORT).show();
                  }

                  @Override
                  public void onStart() {
                        super.onStart();
                        dialog.show();
                        dialog.setTitle("Registering...");
                  }

                  @Override
                  public void onFinish() {
                        super.onFinish();
                        dialog.dismiss();

                  }
            });
      }

      private void getCity(String s) {
            RequestParams params = new RequestParams();
            params.put("state",s);
            client.post(CommonUtil.url+"getCitiesByState.php",params,new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        super.onSuccess(statusCode, headers, response);
                        dialog.dismiss();
                        City.clear();
                        try {
                              if(response.getString("responce").equals("Success")){
                                    JSONArray city = response.getJSONArray("city");
                                    Log.d("-----city-----",city.toString());
                                    for(int i=0;i<city.length();i++){
                                          City.add(city.getString(i));
                                    }
                                    ArrayAdapter adapter = new ArrayAdapter(Register.this,android.R.layout.simple_spinner_dropdown_item,City);
                                    spinner_city.setAdapter(adapter);
                              }else {
                                    Toast.makeText(Register.this, "Something went wrong", Toast.LENGTH_SHORT).show();
                              }
                        } catch (JSONException e) {
                              e.printStackTrace();
                        }
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                        super.onFailure(statusCode, headers, throwable, errorResponse);
                        dialog.dismiss();
                        Toast.makeText(Register.this, "Something went wrong", Toast.LENGTH_SHORT).show();
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        super.onFailure(statusCode, headers, responseString, throwable);
                        dialog.dismiss();
                        Toast.makeText(Register.this, "Something went wrong", Toast.LENGTH_SHORT).show();
                  }

                  @Override
                  public void onStart() {
                        super.onStart();
                        dialog.show();
                  }

                  @Override
                  public void onFinish() {
                        super.onFinish();
                        dialog.dismiss();
                  }
            });
      }

      private void initView() {
            state = findViewById(R.id.state);
            spinner_city = findViewById(R.id.city);
            client = new AsyncHttpClient();

            dialog = new ACProgressFlower.Builder(Register.this)
                        .direction(ACProgressConstant.DIRECT_CLOCKWISE)
                        .themeColor(R.color.grey_700)
                        .bgColor(Color.WHITE)
                        .textAlpha(1)
                        .text("Please wait...")
                        .textColor(Color.BLACK)
                        .speed(15)
                        .bgAlpha(1)
                        .fadeColor(Color.WHITE)
                        .build();

            btnCreateAccount = findViewById(R.id.btnCreateAccount);
            txtSignin = findViewById(R.id.txtSignin);

            name = findViewById(R.id.name);
            email = findViewById(R.id.email);
            contact = findViewById(R.id.contact);
            pass = findViewById(R.id.pass);
            c_pass = findViewById(R.id.c_pass);
            account = findViewById(R.id.account);
            ifsc = findViewById(R.id.ifsc);
            profile_img = findViewById(R.id.profile_img);
          document = findViewById(R.id.document);
      }

    public static String getRealPathFromURI_API19(Context context, Uri uri){
        String filePath = "";
        String wholeID = DocumentsContract.getDocumentId(uri);
        Toast.makeText(context, ""+wholeID, Toast.LENGTH_SHORT).show();
          String id = wholeID.split(":")[1];
        String[] column = { MediaStore.Images.Media.DATA };
        String sel = MediaStore.Images.Media._ID + "=?";
        Cursor cursor = context.getContentResolver().query(MediaStore.Images.Media.EXTERNAL_CONTENT_URI,
              column, sel, new String[]{ id }, null);
        int columnIndex = cursor.getColumnIndex(column[0]);
        if (cursor.moveToFirst()) {
            filePath = cursor.getString(columnIndex);
        }
        cursor.close();
        return filePath;
    }
    public static String getRealPathFromURI_API11to18(Context context, Uri contentUri) {
        String[] proj = { MediaStore.Images.Media.DATA };
        String result = null;

        CursorLoader cursorLoader = new CursorLoader(
              context,
              contentUri, proj, null, null, null);
        Cursor cursor = cursorLoader.loadInBackground();
        if(cursor != null){
            int column_index =
                  cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
            cursor.moveToFirst();
            result = cursor.getString(column_index);
        }
        return result;
    }
    public static String getRealPathFromURI_BelowAPI11(Context context, Uri contentUri){
        String[] proj = { MediaStore.Images.Media.DATA };
        Cursor cursor = context.getContentResolver().query(contentUri, proj, null, null, null);
        int column_index
              = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
        cursor.moveToFirst();
        return cursor.getString(column_index);
    }


    public static String getPath(final Context context, final Uri uri) {

        // check here to KITKAT or new version
        final boolean isKitKat = Build.VERSION.SDK_INT >= Build.VERSION_CODES.KITKAT;

        // DocumentProvider
        if (isKitKat && DocumentsContract.isDocumentUri(context, uri)) {

            // ExternalStorageProvider
            if (isExternalStorageDocument(uri)) {
                final String docId = DocumentsContract.getDocumentId(uri);
                final String[] split = docId.split(":");
                final String type = split[0];

                if ("primary".equalsIgnoreCase(type)) {
                    return Environment.getExternalStorageDirectory() + "/"
                          + split[1];
                }
            }
            // DownloadsProvider
            else if (isDownloadsDocument(uri)) {

                final String id = DocumentsContract.getDocumentId(uri);
                final Uri contentUri = ContentUris.withAppendedId(
                      Uri.parse("content://downloads/public_downloads"),
                      Long.valueOf(id));

                return getDataColumn(context, contentUri, null, null);
            }
            // MediaProvider
            else if (isMediaDocument(uri)) {
                final String docId = DocumentsContract.getDocumentId(uri);
                final String[] split = docId.split(":");
                final String type = split[0];

                Uri contentUri = null;
                if ("image".equals(type)) {
                    contentUri = MediaStore.Images.Media.EXTERNAL_CONTENT_URI;
                } else if ("video".equals(type)) {
                    contentUri = MediaStore.Video.Media.EXTERNAL_CONTENT_URI;
                } else if ("audio".equals(type)) {
                    contentUri = MediaStore.Audio.Media.EXTERNAL_CONTENT_URI;
                }

                final String selection = "_id=?";
                final String[] selectionArgs = new String[] { split[1] };

                return getDataColumn(context, contentUri, selection,
                      selectionArgs);
            }
        }
        // MediaStore (and general)
        else if ("content".equalsIgnoreCase(uri.getScheme())) {

            // Return the remote address
            if (isGooglePhotosUri(uri))
                return uri.getLastPathSegment();

            return getDataColumn(context, uri, null, null);
        }
        // File
        else if ("file".equalsIgnoreCase(uri.getScheme())) {
            return uri.getPath();
        }

        return null;
    }

    public static String getDataColumn(Context context, Uri uri,
                                       String selection, String[] selectionArgs) {

        Cursor cursor = null;
        final String column = "_data";
        final String[] projection = { column };

        try {
            cursor = context.getContentResolver().query(uri, projection,
                  selection, selectionArgs, null);
            if (cursor != null && cursor.moveToFirst()) {
                final int index = cursor.getColumnIndexOrThrow(column);
                return cursor.getString(index);
            }
        } finally {
            if (cursor != null)
                cursor.close();
        }
        return null;
    }

    /**
     * @param uri
     *            The Commanutils to check.
     * @return Whether the Commanutils authority is ExternalStorageProvider.
     */
    public static boolean isExternalStorageDocument(Uri uri) {
        return "com.android.externalstorage.documents".equals(uri
              .getAuthority());
    }

    /**
     * @param uri
     *            The Commanutils to check.
     * @return Whether the Commanutils authority is DownloadsProvider.
     */
    public static boolean isDownloadsDocument(Uri uri) {
        return "com.android.providers.downloads.documents".equals(uri
              .getAuthority());
    }

    /**
     * @param uri
     *            The Commanutils to check.
     * @return Whether the Commanutils authority is MediaProvider.
     */
    public static boolean isMediaDocument(Uri uri) {
        return "com.android.providers.media.documents".equals(uri
              .getAuthority());
    }

    /**
     * @param uri
     *            The Commanutils to check.
     * @return Whether the Commanutils authority is Google Photos.
     */
    public static boolean isGooglePhotosUri(Uri uri) {
        return "com.google.android.apps.photos.content".equals(uri
              .getAuthority());
    }
}
