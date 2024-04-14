import 'package:flutter/material.dart';
import 'package:ui_x/helpers/ColorPalette.dart';

class FormInputTime extends StatelessWidget {
  FormInputTime(
      {Key? key, this.value, this.onChanged,this.label, this.firstDate, this.lastDate})
      : super(key: key);

  final TimeOfDay? value;
  final String? label;
  final TimeOfDay? firstDate;
  final DateTime? lastDate;
  final Function(dynamic)? onChanged;

  @override
  Widget build(BuildContext context) {
    var time = value != null ? "${(value!.hour > 12 ? value!.hour - 12 : value!.hour == 0 ? '12': value!.hour).toString()}:${value!.minute !=0 ? value!.minute : '00' }${value!.hour >= 12 ? ' PM' : ' AM'}" : label;
    return GestureDetector(
      onTap: () async {
        TimeOfDay? pickedTime = await showTimePicker(
          context: context, //context of current state
          initialTime: value ?? TimeOfDay.now(),
          confirmText: "Confirm",
        );
        if (pickedTime != null) {
          onChanged!(pickedTime);
        }
      },
      child: Container(
        padding: EdgeInsets.symmetric(vertical: 6.0, horizontal: 16.0),
        width: double.maxFinite,
        decoration: BoxDecoration(
          color: kcWhite,
          border: Border.all(color: Colors.black.withOpacity(0.25), width: 1.0),
          borderRadius: BorderRadius.circular(4),
        ),
        child: Text(time ?? 'Select Time'),
      ),
    );
  }
}
