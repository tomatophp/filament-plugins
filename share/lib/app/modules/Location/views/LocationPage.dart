import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';

import '/app/shared/views/errors/NotConnectedErrorPage.dart';
import '/app/shared/views/layouts/MasterLayout.dart';
import '/app/shared/views/widgets/LoadingIconWidget.dart';
import '/app/modules/Location/controllers/LocationController.dart';

class LocationPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return GetBuilder<LocationController>(
      init: LocationController(),
      builder: (LocationController controller) {
        return !controller.isConnected
            ? NotConnectedErrorPage()
            : controller.isBusy
                ? LoadingIconWidget(message: "Please wait...")
                : MasterLayout(
                    title: "Location",
                    body:  Obx(() => Scaffold(
                      body: controller.activegps.value == false
                          ? Container(
                        height: Get.height,
                        width: Get.width,
                        padding: EdgeInsets.symmetric(horizontal: 50),
                        child: Center(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.center,
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: <Widget>[
                              Container(
                                height: 250,
                                width: 250,
                                child: Image.asset('assets/images/nogps.png'),
                              ),
                              SizedBox(
                                height: 20,
                              ),
                              Text(
                                'You must activate GPS to get your location',
                                textAlign: TextAlign.center,
                                style: TextStyle(
                                  color: Colors.black,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              SizedBox(
                                height: 40,
                              ),
                              ElevatedButton(
                                onPressed: () {
                                  controller.getUserLocation();
                                },
                                child: Text('try again'),
                              )
                            ],
                          ),
                        ),
                      ) : SafeArea(
                          child: Stack(
                          alignment: Alignment.center,
                          children: <Widget>[
                            Positioned(
                              top: 0,
                              child: Container(
                                height: MediaQuery.of(context).size.height,
                                width: MediaQuery.of(context).size.width,
                                child: GoogleMap(
                                  zoomControlsEnabled: false,
                                  mapType: MapType.normal,
                                  markers: controller.markers,
                                  onCameraMove: controller.onCameraMove,
                                  initialCameraPosition: CameraPosition(
                                      target: controller.initialPos, zoom: 18.0),
                                  onMapCreated: controller.onCreated,
                                  onCameraIdle: () async {
                                    controller.getMoveCamera();
                                  },
                                ),
                              ),
                            ),
                            Positioned(
                              bottom: 20,
                              right: 20,
                              child: FloatingActionButton(
                                onPressed: controller.getUserLocation,
                                backgroundColor: Colors.blueAccent,
                                child: Icon(
                                  Icons.gps_fixed,
                                  color: Colors.white,
                                ),
                              ),
                            ),
                            Positioned(
                                top: 0,
                                child: Container(
                                    color: Colors.white,
                                    height: 150,
                                    width: MediaQuery.of(context).size.width,
                                    child: Container(
                                      padding: EdgeInsets.symmetric(horizontal: 20),
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: <Widget>[
                                          SizedBox(
                                            height: 20,
                                          ),
                                          Text(
                                            "Google Maps",
                                            style: TextStyle(
                                                fontSize: 22,
                                                fontWeight: FontWeight.bold),
                                          ),
                                          SizedBox(
                                            height: 30,
                                          ),
                                          TextField(
                                            maxLines: 1,
                                            controller: controller.locationController,
                                            decoration: InputDecoration(
                                                prefixIcon: Icon(Icons.map),
                                                //hintText: 'CoNstr@seña',
                                                border: OutlineInputBorder(
                                                    borderSide: BorderSide(
                                                        color: Colors.white),
                                                    borderRadius:
                                                    BorderRadius.circular(10))),
                                          ),
                                        ],
                                      ),
                                    ))),
                            Align(
                              alignment: Alignment.center,
                              child: Icon(
                                Icons.location_on,
                                size: 50,
                                color: Colors.redAccent,
                              ),
                            )
                          ],
                        ),
                      ) ,
                    ),
                ),
            );
      },
    );
  }
}

