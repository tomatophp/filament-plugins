import 'package:flutter/material.dart';
import 'package:ui_x/helpers/ColorPalette.dart';

class FormInputDate extends StatelessWidget {
  FormInputDate(
      {Key? key, this.value, this.onChanged,this.label, this.firstDate, this.lastDate})
      : super(key: key);

  final DateTime? value;
  final String? label;
  final DateTime? firstDate;
  final DateTime? lastDate;
  final Function(dynamic)? onChanged;

  @override
  Widget build(BuildContext context) {
    var date = value != null ? "${value!.year}-${value!.month < 10 ? "0" + value!.month.toString() : value!.month}-${value!.day}" : label;
    return GestureDetector(
      onTap: () async {
        DateTime? pickedDate = await showDatePicker(
            context: context, //context of current state
            initialDate: value ?? DateTime.now(),
            firstDate: firstDate ?? DateTime(2000),
            lastDate: lastDate ?? DateTime(2101));
        if (pickedDate != null) {
          onChanged!(pickedDate);
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
        child: Text(date ?? 'Select Date'),
      ),
    );
  }
}
