{{--
    Shared Firebase config for the live-chat widget + admin dashboard.
    Edit ONLY here -- both the user-facing widget and the admin dashboard
    include this one file, so credentials never need to be changed twice.

    Get these values from: Firebase Console -> Project Settings ->
    General -> "Your apps" -> Web app -> SDK setup and configuration.
    The Realtime Database must be created (Firebase Console -> Build ->
    Realtime Database -> Create Database) and its rules set to allow
    read/write (see the rules note at the bottom of chat-widget.blade.php).
--}}
<script>
    window.PME_FIREBASE_CONFIG = {
        apiKey: "YOUR_FIREBASE_API_KEY",
        authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
        databaseURL: "https://YOUR_PROJECT_ID-default-rtdb.firebaseio.com",
        projectId: "YOUR_PROJECT_ID",
        storageBucket: "YOUR_PROJECT_ID.appspot.com",
        messagingSenderId: "YOUR_SENDER_ID",
        appId: "YOUR_APP_ID"
    };
</script>
