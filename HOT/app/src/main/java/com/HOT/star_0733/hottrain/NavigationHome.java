package com.HOT.star_0733.hottrain;

import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.support.v4.content.ContextCompat;
import android.support.v4.widget.SlidingPaneLayout;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.MenuItem;
import android.view.View;
import android.widget.ExpandableListView;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.HOT.star_0733.hottrain.Adapter.MenuNavigationAdapter;

import com.HOT.star_0733.hottrain.R;
import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.auth.api.signin.GoogleSignInClient;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.loopj.android.http.AsyncHttpClient;
import com.squareup.picasso.Picasso;

public class NavigationHome extends AppCompatActivity implements View.OnClickListener {

    SlidingPaneLayout mSlidingPanel;
    View bg;
    TextView address,name;
    FirebaseAuth firebaseAuth = FirebaseAuth.getInstance();
    GoogleSignInClient mGoogleSignInClient;
    Uri uri;
    AsyncHttpClient httpClient;
    SharedPreferences sharedPreferences;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_navigation_home);
        mSlidingPanel = (SlidingPaneLayout) findViewById(R.id.SlidingPanel);
        mSlidingPanel.setPanelSlideListener(panelListener);
        mSlidingPanel.setParallaxDistance(100);
        mSlidingPanel.setSliderFadeColor(ContextCompat.getColor(this, android.R.color.transparent));

        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        ActionBar actionBar = getSupportActionBar();
        if (actionBar != null) {
            actionBar.setDisplayShowTitleEnabled(true);
            actionBar.setTitle("Home");
            actionBar.setDisplayHomeAsUpEnabled(true);
            actionBar.setHomeAsUpIndicator(R.drawable.back);
            //actionBar.setDisplayOptions(ActionBar.DISPLAY_SHOW_CUSTOM);
            //   actionBar.setCustomView(R.layout.center_text);
        }

        bg = findViewById(R.id.imageView);
        name= findViewById(R.id.name);
        address = findViewById(R.id.address);
        FirebaseUser user = FirebaseAuth.getInstance().getCurrentUser();
        String email="";

        if (user != null) {
            email  = user.getEmail();
        }

        if (user != null) {
            uri=user.getPhotoUrl();
        }
        address.setText(email);
        name.setText(user.getDisplayName());
        Picasso.get().load(uri.toString()).into((ImageView) bg);

        final String[] mGroups = {
                "Stories",
                "Feed",
                "Messages",
                "Profile"
        };

        final String[][] mChilds = {
                {"Popular","Recent","Favourite"},
                {},
                {},
                {}
        };

        ExpandableListView listMenu = (ExpandableListView) findViewById(R.id.menu_list);
        MenuNavigationAdapter adapter = new MenuNavigationAdapter(this, mGroups, mChilds);
        listMenu.setAdapter(adapter);
        listMenu.expandGroup(0);
        listMenu.setOnChildClickListener(new ExpandableListView.OnChildClickListener() {
            @Override
            public boolean onChildClick(ExpandableListView expandableListView, View view, int groupPosition, int childPosition, long l) {
                Toast.makeText(NavigationHome.this, "Menu "+mChilds[groupPosition][childPosition]+" clicked!", Toast.LENGTH_SHORT).show();
                mSlidingPanel.closePane();
                return false;
            }
        });

        listMenu.setOnGroupClickListener(new ExpandableListView.OnGroupClickListener() {
            @Override
            public boolean onGroupClick(ExpandableListView expandableListView, View view, int groupPosition, long l) {
                if(groupPosition > 0) {
                    Toast.makeText(NavigationHome.this, "Menu " + mGroups[groupPosition] + " clicked!", Toast.LENGTH_SHORT).show();
                    mSlidingPanel.closePane();
                }
                return false;
            }
        });

        initComponent();
    }

    SlidingPaneLayout.PanelSlideListener panelListener = new SlidingPaneLayout.PanelSlideListener(){
        @Override
        public void onPanelClosed(View arg0) {
            // TODO Auto-genxxerated method stub
            getWindow().setStatusBarColor(getResources().getColor(R.color.gray));
        }

        @Override
        public void onPanelOpened(View arg0) {
            // TODO Auto-generated method stub
            getWindow().setStatusBarColor(getResources().getColor(R.color.red));

        }

        @Override
        public void onPanelSlide(View arg0, float arg1) {
            // TODO Auto-generated method stub

        }

    };

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                if(mSlidingPanel.isOpen()){
                    mSlidingPanel.closePane();
                }else{
                    mSlidingPanel.openPane();
                }
                break;
            default:
                break;
        }
        return true;
    }

    @Override
    public void onClick(View view) {
        switch (view.getId()) {
            case R.id.btnLogout:
                //Toast.makeText(this, "Button logout clicked!", Toast.LENGTH_SHORT).show();
                doLogout();
                break;
            case R.id.btnSetting:
                Toast.makeText(this, "Button setting clicked!", Toast.LENGTH_SHORT).show();
                break;
            default:
                break;
        }
    }
    public void doLogout()
    {
        firebaseAuth.signOut();
        GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                .requestEmail()
                .build();
        mGoogleSignInClient = GoogleSignIn.getClient(this, gso);
        mGoogleSignInClient.signOut();

        finish();
        Intent intent = new Intent(this,User_login.class);
        startActivity(intent);

    }

    private void initComponent() {
//        recyclerView = (RecyclerView) findViewById(R.id.recyclerView);
//        recyclerView.setLayoutManager(new GridLayoutManager(this, 2));
//        recyclerView.addItemDecoration(new SpacingItemDecoration(2, dpToPx(this, 15), true));
//        recyclerView.setHasFixedSize(true);
//        recyclerView.setNestedScrollingEnabled(false);
//
//        items = new ArrayList<>();
//        items.add(new CardCategory(R.drawable.loginlogo,"Ledger"));
//        items.add(new CardCategory(R.drawable.loginlogo,"Account"));
//        items.add(new CardCategory(R.drawable.loginlogo,"Items"));
//        items.add(new CardCategory(R.drawable.loginlogo,"Accounts"));
//        items.add(new CardCategory(R.drawable.loginlogo,"Stocks"));
//        items.add(new CardCategory(R.drawable.loginlogo,"Stocks"));
//        items.add(new CardCategory(R.drawable.loginlogo,"Accounts"));
//        items.add(new CardCategory(R.drawable.loginlogo,"Stocks"));
//        items.add(new CardCategory(R.drawable.loginlogo,"Stocks"));
//        items.add(new CardCategory(R.drawable.loginlogo,"Accounts"));
//        items.add(new CardCategory(R.drawable.loginlogo,"Stocks"));
//        items.add(new CardCategory(R.drawable.loginlogo,"Stocks"));
//
//        //set data and list adapter
//        mAdapter = new AdapterCardLayout(this, items);
//        recyclerView.setAdapter(mAdapter);
//
//        // on item list clicked
//        mAdapter.setOnItemClickListener(new AdapterCardLayout.OnItemClickListener() {
//            @Override
//            public void onItemClick(View view, CardCategory obj, int position) {
//                Toast.makeText(MainActivity.this, obj.getCardcatagory()+"-"+position, Toast.LENGTH_SHORT).show();
//                //Snackbar.make(parent_view, "Item " + obj.getCardcatagory() + " clicked", Snackbar.LENGTH_SHORT).show();
//
//                if(position==0)
//                {
//                    Intent intent = new Intent(MainActivity.this,Ledger_Details.class);
//                    startActivity(intent);
//                }
//                else if(position==1)
//                {
//                    Intent intent = new Intent(MainActivity.this,Main2Activity.class);
//                    startActivity(intent);
//                }
//
//            }
//        });

    }

    //private void Jsondata(List<CardCategory> items) {


//    public static int dpToPx(Context c, int dp) {
//        Resources r = c.getResources();
//        return Math.round(TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, dp, r.getDisplayMetrics()));
//    }


}
